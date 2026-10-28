<?php
/**
 * Self-contained tests for PHP_Puro (requests/, src/Client.php, collections/).
 * Nothing here talks to Purolator: HTTP goes to a local mock (tests/mock_server.php),
 * and credentials, the token cache and .env are all fake or switched off.
 *
 *   php tests\run.php
 *
 * Exit code 0 = every check passed.
 */
declare(strict_types=1);

require dirname(__DIR__) . '/src/Client.php';

use Puro\Client;

$root = dirname(__DIR__);
$pass = 0;
$fail = 0;

function check(bool $ok, string $label, string $detail = ''): void
{
    global $pass, $fail;
    if ($ok) {
        $pass++;
        return;
    }
    $fail++;
    echo "  FAIL: {$label}" . ($detail !== '' ? " ({$detail})" : '') . "\n";
}

function section(string $title): void
{
    echo "\n== {$title}\n";
}

// ---- a clean, fake environment for everything below ----------------------------
foreach (['REST_ClientId', 'REST_Secret', 'REST_XApiKey', 'AccountNumber', 'Different_AccountNumber', 'REST_Token',
          'PUROLATOR_CLIENT_ID', 'PUROLATOR_CLIENT_SECRET', 'PUROLATOR_X_API_KEY', 'PUROLATOR_ACCOUNT',
          'PUROLATOR_ACCOUNT_2', 'PUROLATOR_TOKEN', 'PUROLATOR_LANGUAGE', 'baseUrl', 'rateBaseUrl'] as $k) {
    putenv($k);   // unset anything real
}
$fakeEnv = [
    'PUROLATOR_ENV'           => 'dev',
    'REST_ClientId'           => 'fake-id',
    'REST_Secret'             => 'fake-secret',
    'REST_XApiKey'            => 'fake-key',
    'AccountNumber'           => '9999999',
    'Different_AccountNumber' => '8888888',
    'PURO_NO_ENV_FILE'        => '1',
];
foreach ($fakeEnv as $k => $v) {
    putenv("{$k}={$v}");
}

// ---- 1. every request definition ---------------------------------------------------
section('1. Request definitions');
$defs = [];
foreach (glob($root . '/requests/*/*.php') ?: [] as $file) {
    $def = require $file;
    check(is_array($def), 'returns an array', basename($file));
    if (!is_array($def)) {
        continue;
    }
    $def['_file'] = $file;
    $def['_dir']  = basename(dirname($file));
    $defs[] = $def;
}
check(count($defs) > 0, 'at least one request script found');
echo '  ' . count($defs) . " request scripts\n";

$seen = [];
foreach ($defs as $d) {
    $id = $d['folder'] . ' / ' . $d['name'];
    check(!isset($seen[$id]), 'unique folder/name', $id);
    $seen[$id] = true;
    check(in_array($d['method'], ['GET', 'POST', 'PUT'], true), 'known method', $id);
    check(str_starts_with($d['url'], '{{baseUrl}}') || str_starts_with($d['url'], '{{rateBaseUrl}}'), 'URL starts with the host variable', $id);
    check(in_array($d['auth'], ['bearer', 'basic'], true), 'known auth', $id);
    if ($d['auth'] === 'bearer') {
        check(array_key_exists('x-api-key', $d['headers']), 'sends x-api-key (lowercase)', $id);
    }
    if ($d['method'] !== 'GET') {
        check(isset($d['body']) && json_decode(preg_replace('/\{\{[A-Za-z0-9_]+\}\}/', 'x', $d['body']) ?? '', true) !== null, 'body is JSON with placeholders filled', $id);
    }
}

// ---- 2. variable resolution and the PIN chain -----------------------------------
section('2. Variables resolve; every PIN a request reads is captured by an earlier request');
$produced = [];   // variable => list of [folder, order, name]
foreach ($defs as $d) {
    foreach (($d['capture']['vars'] ?? []) as $v) {
        $produced[$v][] = [$d['_dir'], (int)$d['order'], $d['name']];
    }
}
$allCaptured = array_fill_keys(array_keys($produced), '329000000001');

// Inputs only the user can supply (no earlier request produces them). The tracking
// requests read these from the Postman environment; here they come from the environment
// or from NAME=value on the command line. A new unknown variable must fail this test.
$userInputs = ['trackingId2', 'trackingId3', 'trackReference', 'referenceData'];
foreach ($userInputs as $v) {
    $allCaptured[$v] = '329000000009';
}

$expectedMissing = 0;
foreach ($defs as $d) {
    $id = $d['folder'] . ' / ' . $d['name'];

    // (a) with nothing captured yet, only capturable variables may be missing.
    $missing = null;
    try {
        Client::prepare($d, true, [], 'dev');
    } catch (RuntimeException $e) {
        preg_match('/\{\{([A-Za-z0-9_]+)\}\}/', $e->getMessage(), $m);
        $missing = $m[1] ?? '?';
    }
    if ($missing !== null) {
        $expectedMissing++;
        if (in_array($missing, $userInputs, true)) {
            continue;   // supplied by the user, not captured
        }
        check(isset($produced[$missing]), 'missing variable is captured by some request', "{$id} needs {{$missing}}");
        // The producer must run before the consumer: earlier folder, or same folder with a lower order.
        $ok = false;
        foreach ($produced[$missing] ?? [] as [$pdir, $pord]) {
            if ($pdir < $d['_dir'] || ($pdir === $d['_dir'] && $pord < (int)$d['order'])) {
                $ok = true;
            }
        }
        check($ok, 'a producer of that variable runs earlier', "{$id} needs {{$missing}}");
    }

    // (b) with every capturable variable present, everything must resolve.
    try {
        $req = Client::prepare($d, true, $allCaptured, 'dev');
    } catch (RuntimeException $e) {
        check(false, 'resolves once PINs are captured', $id . ': ' . $e->getMessage());
        continue;
    }
    check(!str_contains($req['url'], '{{'), 'URL has no placeholder left', $id);
    check(!str_contains(implode('', $req['headers']), '{{'), 'headers have no placeholder left', $id);
    if ($req['body'] !== null) {
        check(json_decode($req['body'], true) !== null, 'resolved body is valid JSON', $id);
        check(!str_contains($req['body'], '{{'), 'body has no placeholder left', $id);
    }
    // Shipment dates: today + dateOffset in Toronto time.
    if (isset($d['dateOffset']) && str_contains((string)($d['body'] ?? ''), '{{Current_Date}}')) {
        $want = (new DateTimeImmutable('now', new DateTimeZone('America/Toronto')))
            ->modify(sprintf('%+d days', (int)$d['dateOffset']))->format('Y-m-d');
        check(str_contains((string)$req['body'], $want), 'shipment date = today + offset', "{$id} wants {$want}");
    }
}
echo "  {$expectedMissing} requests read a PIN captured by an earlier one\n";

// Guard: a create/validate request that sends {{Current_Date}} must carry the +N day script,
// otherwise it would silently ship "today". (The date check above skips requests without one.)
$shipDated = 0;
foreach ($defs as $d) {
    if ($d['method'] === 'POST' && str_contains($d['url'], 'createShipmentPath') && str_contains((string)($d['body'] ?? ''), '{{Current_Date}}')) {
        // The Consolidation Check requests carry no date script in the source collection, so
        // they ship "today"; that is the collection's behaviour, not a conversion gap.
        if ($d['_dir'] === '11-Consolidation-Check') {
            continue;
        }
        $shipDated++;
        check(isset($d['dateOffset']) && $d['dateOffset'] > 0, 'create/validate request has a shipment date offset', $d['folder'] . ' / ' . $d['name']);
    }
}
check($shipDated > 100, 'found the create/validate requests', (string)$shipDated);
echo "  {$shipDated} create/validate requests carry a shipment date offset\n";

// The override variable (shipDaysAhead) changes the date; NAME=value and PUROLATOR_SHIP_DAYS_AHEAD both work.
$withVar = array_values(array_filter($defs, fn($d) => isset($d['dateOffsetVar']) && str_contains((string)($d['body'] ?? ''), '{{Current_Date}}')))[0] ?? null;
check($withVar !== null, 'a request supports the shipDaysAhead override');
if ($withVar !== null) {
    $today = (new DateTimeImmutable('now', new DateTimeZone('America/Toronto')))->format('Y-m-d');
    check(str_contains(Client::prepare($withVar, true, $allCaptured, 'dev', ['shipDaysAhead' => '0'])['body'], $today), 'shipDaysAhead=0 ships today');
    putenv('PUROLATOR_SHIP_DAYS_AHEAD=0');
    check(str_contains(Client::prepare($withVar, true, $allCaptured, 'dev')['body'], $today), 'PUROLATOR_SHIP_DAYS_AHEAD=0 ships today');
    putenv('PUROLATOR_SHIP_DAYS_AHEAD');
}

// Aliases: PUROLATOR_* names fill the same variables.
putenv('REST_XApiKey');
putenv('PUROLATOR_X_API_KEY=alias-key');
$probe = array_values(array_filter($defs, fn($d) => $d['auth'] === 'bearer' && $d['method'] === 'GET'))[0];
check(Client::prepare($probe, false, [], 'dev')['headers']['x-api-key'] === 'alias-key', 'PUROLATOR_X_API_KEY alias works');
putenv('PUROLATOR_X_API_KEY');
putenv('REST_XApiKey=fake-key');

// ---- 3. the Postman collections match the scripts -----------------------------------
section('3. Postman collections');
$byId = [];
foreach ($defs as $d) {
    $byId[$d['folder'] . ' / ' . $d['name']] = $d;
}
foreach (['Development' => 'https://shipapi-sandbox.purolator.com', 'Production' => 'https://shipapi.purolator.com'] as $label => $host) {
    $file = "{$root}/collections/Purolator-REST-{$label}.postman_collection.json";
    $c = json_decode((string)@file_get_contents($file), true);
    check(is_array($c), "{$label}: valid JSON");
    if (!is_array($c)) {
        continue;
    }
    check(($c['info']['schema'] ?? '') === 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json', "{$label}: v2.1 schema");
    $vars = array_column($c['variable'], 'value', 'key');
    check(($vars['baseUrl'] ?? '') === $host, "{$label}: baseUrl", (string)($vars['baseUrl'] ?? ''));
    check(($vars['authScope'] ?? '') === (str_contains($host, 'sandbox') ? 'portal_api_sandbox' : 'portal_api'), "{$label}: authScope");

    $n = 0;
    foreach ($c['item'] as $folder) {
        foreach ($folder['item'] as $it) {
            $n++;
            $id = $folder['name'] . ' / ' . $it['name'];
            $d  = $byId[$id] ?? null;
            check($d !== null, "{$label}: request exists in requests/", $id);
            if ($d === null) {
                continue;
            }
            check($it['request']['method'] === $d['method'], "{$label}: method", $id);
            check($it['request']['url']['raw'] === $d['url'], "{$label}: url", $id);
            $hdr = array_column($it['request']['header'], 'value', 'key');
            check($hdr === $d['headers'], "{$label}: headers", $id);
            check(($it['request']['body']['raw'] ?? null) === ($d['body'] ?? null), "{$label}: body", $id);
            $listens = array_column($it['event'] ?? [], 'listen');
            check(in_array('prerequest', $listens, true) === isset($d['dateOffset']), "{$label}: date script iff dateOffset", $id);
            check(in_array('test', $listens, true) === isset($d['capture']), "{$label}: capture script iff capture", $id);
        }
    }
    check($n === count($defs), "{$label}: request count", "{$n} vs " . count($defs));
}

// ---- 4. end to end against the local mock ---------------------------------------------
section('4. End to end against a local mock API');

$php = PHP_BINARY;
$ini = php_ini_loaded_file();
$phpArgs = $ini ? ['-c', $ini] : [];
$state = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'puro-test-state-' . getmypid() . '.json';
@unlink($state);

/** Pick a free port. */
$port = 0;
for ($p = 18080; $p < 18180; $p++) {
    $s = @stream_socket_server("tcp://127.0.0.1:{$p}");
    if ($s) {
        fclose($s);
        $port = $p;
        break;
    }
}
check($port > 0, 'found a free port');

// Minimal environment: nothing real can reach the subprocesses.
$sys = [];
foreach (['SystemRoot', 'windir', 'PATH', 'Path', 'TEMP', 'TMP', 'COMSPEC', 'HOME'] as $k) {
    $v = getenv($k);
    if ($v !== false) {
        $sys[$k] = $v;
    }
}
$childEnv = $sys + $fakeEnv + [
    'baseUrl'          => "http://127.0.0.1:{$port}",
    'PURO_STATE_FILE'  => $state,
    // user-supplied tracking inputs, via the PUROLATOR_VAR_<NAME> form
    'PUROLATOR_VAR_trackingId2'    => '329000000009',
    'PUROLATOR_VAR_trackingId3'    => '329000000010',
    'PUROLATOR_VAR_trackReference' => 'REFTEST',
    'PUROLATOR_VAR_referenceData'  => 'REFTEST',
];

/** Run a PHP script and return [exit code, stdout, stderr]. */
$runPhp = static function (array $args, array $env) use ($php, $phpArgs, $root): array {
    $proc = proc_open(array_merge([$php], $phpArgs, $args), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $root, $env);
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    return [proc_close($proc), (string)$out, (string)$err];
};

$server = proc_open(
    array_merge([$php], $phpArgs, ['-S', "127.0.0.1:{$port}", $root . '/tests/mock_server.php']),
    [1 => ['file', sys_get_temp_dir() . '/puro-mock.out', 'w'], 2 => ['file', sys_get_temp_dir() . '/puro-mock.err', 'w']],
    $srvPipes,
    $root,
    $sys
);
$up = false;
for ($i = 0; $i < 50 && !$up; $i++) {
    $c = @fsockopen('127.0.0.1', $port, $en, $es, 0.2);
    if ($c) {
        fclose($c);
        $up = true;
    } else {
        usleep(100000);
    }
}
check($up, 'mock server started');

try {
    if ($up) {
        // Token script.
        [$code, $out] = $runPhp(['requests/00-Auth/AUTH-OKTA-Token.php'], $childEnv);
        check($code === 0, 'token script exits 0', trim($out));
        $st = json_decode((string)@file_get_contents($state), true);
        check(($st['dev']['REST_Token'] ?? '') === 'mock-token', 'token cached in the state file');

        // A bad secret is rejected by the gateway: exit 1 (HTTP error), nothing cached.
        $badState = $state . '.bad';
        [$code, , $err] = $runPhp(['requests/00-Auth/AUTH-OKTA-Token.php'], ['REST_Secret' => 'wrong', 'PURO_STATE_FILE' => $badState] + $childEnv);
        check($code === 1, 'wrong secret: HTTP 401 gives exit 1', "exit {$code}");
        check(!is_file($badState), 'wrong secret: nothing cached');

        // Missing credentials: exit 2 and a clear message, no request sent.
        $noCreds = $childEnv;
        unset($noCreds['REST_Secret'], $noCreds['REST_ClientId']);
        $noCreds['PURO_STATE_FILE'] = $state . '.none';
        [$code, , $err] = $runPhp(['requests/00-Auth/AUTH-OKTA-Token.php'], $noCreds);
        check($code === 2 && str_contains($err, 'REST_ClientId'), 'missing credentials: exit 2 naming the variable', trim($err));

        // Dry run: succeeds without a server, credentials hidden.
        [$code, $out] = $runPhp(['requests/02-Shipping/SHIP-Domestic-Basic.php', '--dry-run'], ['baseUrl' => 'http://127.0.0.1:1'] + $childEnv);
        check($code === 0 && str_contains($out, '<hidden>') && !str_contains($out, 'fake-key'), 'dry run hides credentials and sends nothing');

        // NAME=value on the command line fills a placeholder (and rateBaseUrl follows baseUrl).
        $noInputs = $childEnv;
        unset($noInputs['PUROLATOR_VAR_referenceData']);
        [$code, $out, $err] = $runPhp(['requests/05-Tracking/TRACK-V2-Reference.php', 'referenceData=ABC123'], $noInputs);
        check($code === 0, 'NAME=value fills {{referenceData}}', trim($err . $out));
        [$code, , $err] = $runPhp(['requests/05-Tracking/TRACK-V2-Reference.php'], $noInputs);
        check($code === 2 && str_contains($err, 'referenceData'), 'missing user input: exit 2 naming it', trim($err));

        // Forced server error: exit 1.
        [$code] = $runPhp(['requests/00-Auth/AUTH-OKTA-Token.php'], ['baseUrl' => "http://127.0.0.1:{$port}/fail"] + $childEnv);
        check($code === 1, 'HTTP 500 gives exit 1');

        // Every folder, in order, the way a user would run it. The mock accepts a request only
        // when the gateway rules hold (lowercase x-api-key, bearer token, valid JSON body, no
        // {{placeholder}}), so a pass here also proves the PIN chain works across scripts.
        $folders = array_map('basename', glob($root . '/requests/*', GLOB_ONLYDIR) ?: []);
        sort($folders, SORT_STRING);
        foreach ($folders as $f) {
            [$code, $out] = $runPhp(['bin/run-folder.php', $f], $childEnv);
            preg_match('/Done: (\d+) ok, (\d+) not ok/', $out, $m);
            check($code === 0 && ($m[2] ?? '1') === '0', "folder {$f}: all scripts ok", ($m[0] ?? 'no summary') . ' ' . substr(trim($out), -300));
            echo '  ' . str_pad($f, 36) . ($m[1] ?? '?') . " ok\n";
        }
        $st = json_decode((string)@file_get_contents($state), true);
        check(($st['dev']['PIN'] ?? '') === '329000000001', 'shipment PIN captured into the state file');
    }
} finally {
    if (is_resource($server)) {
        proc_terminate($server);
        proc_close($server);
    }
    foreach ([$state, $state . '.bad', $state . '.none'] as $f) {
        @unlink($f);
    }
}

// ---- result ---------------------------------------------------------------------------------
echo "\n" . ($fail === 0 ? 'OK' : 'FAILED') . ": {$pass} checks passed, {$fail} failed\n";
exit($fail === 0 ? 0 : 1);
