<?php
/**
 * Turn the Purolator_2026 REST request files (Postman YAML) into one runnable
 * PHP script per request under requests/<folder>/.
 *
 * Usage (from the PHP_Puro folder):
 *   php tools/convert_yaml.php [path to "Purolator REST - Development"]
 *
 * Default source: ../Purolator_2026/Purolator REST — Development
 * Development and Production request files are identical (only the host
 * differs), so one set of scripts serves both tiers (PUROLATOR_ENV=dev|prod).
 */
declare(strict_types=1);

require __DIR__ . '/MiniYaml.php';

$root = dirname(__DIR__);
$src  = $argv[1] ?? $root . '/../Purolator_2026/Purolator REST — Development';
if (!is_dir($src)) {
    fwrite(STDERR, "Source folder not found: {$src}\n");
    exit(2);
}

/** "02 · Shipping" -> "02-Shipping" (ASCII only, safe for any file system). */
function slug(string $text): string
{
    $s = trim((string)preg_replace('/[^A-Za-z0-9]+/', '-', $text), '-');
    return $s !== '' ? $s : 'x';
}

/** Single-quoted PHP string literal. */
function q(string $s): string
{
    return "'" . str_replace(['\\', "'"], ['\\\\', "\\'"], $s) . "'";
}

/** PHP list of strings, e.g. ['a', 'b']. */
function qlist(array $items): string
{
    return '[' . implode(', ', array_map('q', $items)) . ']';
}

/** Pull the PIN-capture settings out of the Postman scripts. */
function captureFrom(array $scripts): ?array
{
    $code = '';
    foreach ($scripts as $s) {
        if (($s['type'] ?? '') === 'afterResponse') {
            $code .= "\n" . ($s['code'] ?? '');
        }
    }
    // Ignore commented-out lines.
    $live = implode("\n", array_filter(explode("\n", $code), fn($l) => !str_starts_with(ltrim($l), '//')));

    if (str_contains($live, "set('REST_Token'")) {
        return ['token' => true];
    }
    $vars = [];
    $docOrSeed = false;
    if (preg_match('/const DOC_PIN_VARS = \[(.*?)\]/s', $live, $m)) {
        preg_match_all('/[\'"]([A-Za-z0-9_]+)[\'"]/', $m[1], $n);
        $vars = array_merge($vars, $n[1]);
        $docOrSeed = true;
    }
    if (preg_match('/const SEED_PIN_VAR = [\'"]([A-Za-z0-9_]+)[\'"]/', $live, $m)) {
        $vars[] = $m[1];
        $docOrSeed = true;
    }
    if (preg_match('/const PIN_VARS = \[(.*?)\]/s', $live, $m)) {
        preg_match_all('/[\'"]([A-Za-z0-9_]+)[\'"]/', $m[1], $n);
        $vars = array_merge($vars, $n[1]);
    }
    if (preg_match('/environment\.set\(\'PIN\'/', $live)) {
        $vars[] = 'PIN';
    }
    $vars = array_values(array_unique($vars));
    if (!$vars) {
        return null;
    }
    // Document and seed requests fall back to a plain "pin" key; the rest use shipmentPIN only.
    return ['vars' => $vars, 'keys' => $docOrSeed ? ['shipmentpin', 'pin'] : ['shipmentpin']];
}

/** Days added to the shipment date, from "const DAYS_AHEAD = 5;" in the pre-request script. */
function dateOffsetFrom(array $scripts): ?int
{
    foreach ($scripts as $s) {
        if (($s['type'] ?? '') === 'beforeRequest' && preg_match('/const DAYS_AHEAD\s*=\s*(\d+)/', $s['code'] ?? '', $m)) {
            return (int)$m[1];
        }
    }
    return null;
}

$folders = [];
foreach (scandir($src) ?: [] as $dir) {
    if ($dir === '.' || $dir === '..' || $dir[0] === '.' || !is_dir("{$src}/{$dir}")) {
        continue;
    }
    $folders[] = $dir;
}
sort($folders, SORT_STRING);

// Start clean so renamed or removed requests do not linger.
$outRoot = $root . '/requests';
if (is_dir($outRoot)) {
    foreach (glob($outRoot . '/*/*.php') ?: [] as $old) {
        unlink($old);
    }
}

$total = 0;
$perFolder = [];
foreach ($folders as $folder) {
    $folderSlug = slug($folder);
    $files = glob("{$src}/{$folder}/*.request.yaml") ?: [];
    if (!$files) {
        continue;
    }
    $outDir = "{$outRoot}/{$folderSlug}";
    if (!is_dir($outDir)) {
        mkdir($outDir, 0775, true);
    }
    $used = [];
    foreach ($files as $file) {
        $y = MiniYaml::parse((string)file_get_contents($file));
        // The file name is the request name. (A few files carry a "name:" key that
        // an English request and its French twin share, so it cannot tell them apart.)
        $name = (string)preg_replace('/\.request\.yaml$/', '', basename($file));

        // Unique ASCII file name.
        $base = slug($name);
        $slug = $base;
        for ($n = 2; isset($used[strtolower($slug)]); $n++) {
            $slug = "{$base}-{$n}";
        }
        $used[strtolower($slug)] = true;

        $scripts   = $y['scripts'] ?? [];
        $headers   = $y['headers'] ?? [];
        $authType  = $y['auth']['type'] ?? 'none';
        $method    = (string)$y['method'];
        $url       = (string)$y['url'];
        $offset    = dateOffsetFrom($scripts);
        $capture   = captureFrom($scripts);
        $body      = (($y['body']['type'] ?? '') === 'json') ? (string)$y['body']['content'] : null;
        $desc      = trim((string)($y['description'] ?? ''));

        // Guard the here-doc delimiters.
        foreach ([$body, $desc] as $text) {
            if ($text !== null && (str_contains($text, 'PURO_BODY') || str_contains($text, 'PURO_TEXT'))) {
                throw new RuntimeException("Here-doc delimiter clash in {$file}");
            }
        }

        $php  = "<?php\n";
        $php .= "/**\n";
        $php .= ' * ' . str_replace('*/', '* /', "{$folder} / {$name}") . "\n";
        $php .= " * {$method} {$url}\n";
        $php .= " *\n";
        $php .= " * Run:  php requests/{$folderSlug}/{$slug}.php [--dry-run] [--save]\n";
        $php .= " * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)\n";
        $php .= " */\n";
        $php .= "declare(strict_types=1);\n\n";
        $php .= "require_once dirname(__DIR__, 2) . '/src/Client.php';\n\n";
        $php .= "return \\Puro\\Client::runIfMain([\n";
        $php .= "    'folder' => " . q($folder) . ",\n";
        $php .= "    'name'   => " . q($name) . ",\n";
        $php .= "    'order'  => " . (int)($y['order'] ?? 0) . ",\n";
        $php .= "    'method' => " . q($method) . ",\n";
        $php .= "    'url'    => " . q($url) . ",\n";
        $php .= "    'headers' => [\n";
        foreach ($headers as $hk => $hv) {
            $php .= '        ' . q((string)$hk) . ' => ' . q((string)$hv) . ",\n";
        }
        $php .= "    ],\n";
        $php .= "    'auth'   => " . q($authType) . ",\n";
        if ($authType === 'basic') {
            $c = $y['auth']['credentials'] ?? [];
            $php .= "    'basic'  => ['username' => " . q((string)($c['username'] ?? '')) . ", 'password' => " . q((string)($c['password'] ?? '')) . "],\n";
        }
        if ($offset !== null) {
            $php .= "    'dateOffset' => {$offset},\n";
        }
        if ($capture !== null) {
            if (isset($capture['token'])) {
                $php .= "    'capture' => ['token' => true],\n";
            } else {
                $php .= "    'capture' => ['vars' => " . qlist($capture['vars']) . ", 'keys' => " . qlist($capture['keys']) . "],\n";
            }
        }
        $php .= "    'description' => <<<'PURO_TEXT'\n{$desc}\nPURO_TEXT,\n";
        if ($body !== null) {
            $php .= "    'body' => <<<'PURO_BODY'\n{$body}\nPURO_BODY,\n";
        }
        $php .= "], __FILE__);\n";

        file_put_contents("{$outDir}/{$slug}.php", $php);
        $total++;
        $perFolder[$folderSlug] = ($perFolder[$folderSlug] ?? 0) + 1;
    }
}

foreach ($perFolder as $f => $n) {
    echo str_pad($f, 40) . $n . "\n";
}
echo "Wrote {$total} request scripts under requests/\n";
