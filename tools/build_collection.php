<?php
/**
 * Build the Postman collections (Development and Production) from the PHP
 * request scripts in requests/. Nothing is sent: each script is included and
 * its definition array is read.
 *
 * Usage (from the PHP_Puro folder):
 *   php tools/build_collection.php
 *
 * Output: collections/Purolator-REST-Development.postman_collection.json
 *         collections/Purolator-REST-Production.postman_collection.json
 * Import them in Postman (Import > File). No environment is written: create
 * one per tier and fill in your own credentials (see README.md).
 */
declare(strict_types=1);

require dirname(__DIR__) . '/src/Client.php';

use Puro\Client;

$root = dirname(__DIR__);

// ---- load every request definition -------------------------------------------
$defs = [];
$folderDirs = glob($root . '/requests/*', GLOB_ONLYDIR) ?: [];
sort($folderDirs, SORT_STRING);
foreach ($folderDirs as $dir) {
    foreach (glob($dir . '/*.php') ?: [] as $file) {
        $def = require $file;   // runIfMain() returns the array when the file is included
        if (!is_array($def) || !isset($def['url'], $def['method'])) {
            fwrite(STDERR, "Not a request definition: {$file}\n");
            exit(2);
        }
        $def['_dir'] = basename($dir);
        $defs[] = $def;
    }
}
if (!$defs) {
    fwrite(STDERR, "No request scripts found. Run tools/convert_yaml.php first.\n");
    exit(2);
}

// Folder by folder, then the original Postman order, then name.
usort($defs, static fn(array $a, array $b): int =>
    [$a['_dir'], $a['order'] ?? 0, $a['name']] <=> [$b['_dir'], $b['order'] ?? 0, $b['name']]);

// ---- JavaScript snippets (kept short; the PHP scripts do the same in PHP) -----

/** Collection pre-request: carrier-time dates and a unique RequestReference. */
const COLLECTION_PREREQUEST = <<<'JS'
// Collection pre-request: dates in Purolator's time zone and a unique RequestReference.
// (Purolator rejects a shipment date that is "yesterday" in Eastern time: 9001-11.)
const now = new Date();
const pad = n => String(n).padStart(2, '0');
const day = o => new Date(now.getTime() + o * 86400000).toLocaleDateString('en-CA', { timeZone: 'America/Toronto' });
const today = day(0);
pm.environment.set('Current_Date', today);
pm.environment.set('manifestDate', today);          // summary manifest window: today-10 .. today
pm.environment.set('manifestDateOld', day(-11));    // just outside the window
pm.environment.set('manifestDateFuture', day(1));
pm.environment.set('shipmentDateFar', day(15));     // beyond the +10 day ship window
pm.environment.set('trackDateFrom', day(-30));
pm.environment.set('trackDateTo', today);
// Next business day for pickups: skip Saturday and Sunday (weekday taken in Toronto).
let pickupOffset = 1;
while ([0, 6].includes(new Date(day(pickupOffset) + 'T12:00:00Z').getUTCDay())) pickupOffset++;
pm.environment.set('pickupDate', day(pickupOffset));
pm.environment.set('pickupHistoryFromDate', day(-30));
pm.environment.set('pickupHistoryToDate', today);
pm.environment.set('RequestReference', 'REQ' + now.getFullYear() + pad(now.getMonth() + 1) + pad(now.getDate())
    + pad(now.getHours()) + pad(now.getMinutes()) + pad(now.getSeconds()));
JS;

/** Request pre-request: shipment date = today + N days (Toronto); a run can override N. */
function dateScript(int $days, ?string $overrideVar): string
{
    $js = "// Shipment date is today + {$days} day(s), in Purolator's time zone.\n";
    if ($overrideVar !== null) {
        $js .= "// Set the variable {$overrideVar} to use another offset (for example 0 = ship today).\n"
            . "const _ahead = pm.variables.get('{$overrideVar}');\n"
            . "const DAYS_AHEAD = (_ahead === undefined || _ahead === null || _ahead === '') ? {$days} : Number(_ahead);\n";
    } else {
        $js .= "const DAYS_AHEAD = {$days};\n";
    }
    return $js . "pm.environment.set('Current_Date', new Date(Date.now() + DAYS_AHEAD * 86400000)\n"
        . "    .toLocaleDateString('en-CA', { timeZone: 'America/Toronto' }));";
}

/** Request tests: keep the token, or the shipment PIN for the requests after it. */
function captureScript(array $capture): string
{
    if (!empty($capture['token'])) {
        return "// Keep the bearer token for every other request.\n"
            . "const tokenResponse = pm.response.json();\n"
            . "pm.environment.set('REST_Token', tokenResponse.access_token);\n"
            . "pm.test('token issued', () => pm.expect(tokenResponse.access_token).to.be.a('string'));";
    }
    $vars = json_encode($capture['vars'], JSON_UNESCAPED_SLASHES);
    $keys = json_encode($capture['keys'], JSON_UNESCAPED_SLASHES);
    return <<<JS
// Capture the shipment PIN for the requests that read it later.
const PIN_VARS = {$vars};
const PIN_KEYS = {$keys};   // most specific first
function pinFrom(node, wanted) {
    if (node === null || typeof node !== 'object') return '';
    if (Array.isArray(node)) {
        for (const x of node) { const h = pinFrom(x, wanted); if (h) return h; }
        return '';
    }
    for (const k of Object.keys(node)) {
        const v = node[k];
        if (k.toLowerCase() === wanted) {
            if (typeof v === 'string' && v.trim()) return v.trim();
            if (typeof v === 'number') return String(v);
            if (v && typeof v === 'object' && v.value) return String(v.value).trim();
        }
        const h = pinFrom(v, wanted);
        if (h) return h;
    }
    return '';
}
let body = null;
try { body = pm.response.json(); } catch (e) { body = null; }
let pin = '';
for (const key of PIN_KEYS) { pin = body ? pinFrom(body, key) : ''; if (pin) break; }
if (pin) {
    PIN_VARS.forEach(v => pm.environment.set(v, pin));
    console.log('[pin] ' + pin + ' -> ' + PIN_VARS.join(', '));
} else {
    console.log('[pin] none in the response; ' + PIN_VARS.join(', ') + ' left unchanged');
}
JS;
}

/** Postman "exec" is an array of lines. */
function scriptLines(string $code): array
{
    return explode("\n", $code);
}

// ---- assemble the two collections -----------------------------------------------
$tierNames = ['dev' => 'Development', 'prod' => 'Production'];
foreach ($tierNames as $tier => $label) {
    $t = Client::TIERS[$tier];

    // Group requests into folders (a folder = one requests/<dir>).
    $folders = [];
    foreach ($defs as $d) {
        $item = [
            'name'    => $d['name'],
            'request' => [
                'method'      => $d['method'],
                'header'      => array_map(
                    static fn($k, $v) => ['key' => (string)$k, 'value' => (string)$v, 'type' => 'text'],
                    array_keys($d['headers'] ?? []),
                    array_values($d['headers'] ?? [])
                ),
                'url'         => ['raw' => $d['url']],
                'description' => (string)($d['description'] ?? ''),
            ],
            'response' => [],
        ];

        // Authorization: the token call uses Basic; everything else the collection's bearer token.
        if (($d['auth'] ?? '') === 'basic') {
            $item['request']['auth'] = [
                'type'  => 'basic',
                'basic' => [
                    ['key' => 'username', 'value' => $d['basic']['username'], 'type' => 'string'],
                    ['key' => 'password', 'value' => $d['basic']['password'], 'type' => 'string'],
                ],
            ];
        } elseif (($d['auth'] ?? '') === 'bearer') {
            $item['request']['auth'] = [
                'type'   => 'bearer',
                'bearer' => [['key' => 'token', 'value' => '{{REST_Token}}', 'type' => 'string']],
            ];
        }

        if (isset($d['body'])) {
            $item['request']['body'] = [
                'mode'    => 'raw',
                'raw'     => $d['body'],
                'options' => ['raw' => ['language' => 'json']],
            ];
        }

        $events = [];
        if (isset($d['dateOffset'])) {
            $events[] = ['listen' => 'prerequest', 'script' => ['type' => 'text/javascript', 'exec' => scriptLines(dateScript((int)$d['dateOffset'], $d['dateOffsetVar'] ?? null))]];
        }
        if (isset($d['capture'])) {
            $events[] = ['listen' => 'test', 'script' => ['type' => 'text/javascript', 'exec' => scriptLines(captureScript($d['capture']))]];
        }
        if ($events) {
            $item['event'] = $events;
        }

        $folders[$d['folder']][] = $item;
    }
    ksort($folders, SORT_STRING);   // "00 · Auth", "01 · Estimate", ... sort by their number prefix

    $items = [];
    foreach ($folders as $name => $children) {
        $items[] = ['name' => $name, 'item' => $children];
    }

    // Collection variables: tier host + scope, the rest from Client::DEFAULTS.
    $vars = [
        ['key' => 'baseUrl',     'value' => $t['baseUrl']],
        ['key' => 'rateBaseUrl', 'value' => $t['baseUrl']],
        ['key' => 'authScope',   'value' => $t['authScope']],
    ];
    foreach (Client::DEFAULTS as $k => $v) {
        $vars[] = ['key' => $k, 'value' => $v];
    }

    $count = count($defs);
    // Requests the source collection marks as aligned to the OpenAPI spec but not yet sent live.
    $unverified = count(array_filter($defs, static fn(array $d): bool =>
        stripos((string)($d['description'] ?? ''), 'not yet verified live') !== false));
    $verifiedNote = $unverified > 0
        ? "{$count} requests. {$unverified} of them are marked \"Spec-aligned, not yet verified live\" in their description; the rest were verified live against the Purolator Certification environment."
        : "{$count} requests, each verified live against the Purolator Certification environment.";
    $collection = [
        'info' => [
            'name'        => "Purolator REST - {$label} (PHP_Puro)",
            'description' => "Purolator REST API requests, generated from the PHP scripts in PHP_Puro (tools/build_collection.php).\n\n"
                . "{$verifiedNote} Host: {$t['baseUrl']} (scope {$t['authScope']}).\n\n"
                . "Create a Postman environment with REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber and Different_AccountNumber, "
                . "run 00 - Auth first to fill REST_Token, then run each folder top to bottom. No credentials are stored here.",
            'schema'      => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
        ],
        'item'     => $items,
        'auth'     => ['type' => 'bearer', 'bearer' => [['key' => 'token', 'value' => '{{REST_Token}}', 'type' => 'string']]],
        'event'    => [['listen' => 'prerequest', 'script' => ['type' => 'text/javascript', 'exec' => scriptLines(COLLECTION_PREREQUEST)]]],
        'variable' => $vars,
    ];

    $out = $root . '/collections/Purolator-REST-' . $label . '.postman_collection.json';
    if (!is_dir(dirname($out))) {
        mkdir(dirname($out), 0775, true);
    }
    file_put_contents($out, json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n");
    echo "Wrote {$out} ({$count} requests in " . count($items) . " folders)\n";
}
