<?php
/**
 * Purolator REST client for the PHP_Puro example scripts.
 *
 * Plain PHP 8.1+ with cURL. No Composer, no third-party packages.
 *
 * Every script under requests/ returns a request definition (an array) and,
 * when it is run directly from the command line, hands that definition to
 * Client::run(). Tools (tools/build_collection.php) include the same files and
 * read the arrays without sending anything.
 *
 * Settings come from environment variables, or from a .env file next to this
 * folder (see .env.example). Nothing secret is ever stored in the scripts.
 */
declare(strict_types=1);

namespace Puro;

use RuntimeException;

final class Client
{
    /** Host and OAuth scope per tier. PUROLATOR_ENV picks one (default: dev). */
    public const TIERS = [
        'dev'  => ['baseUrl' => 'https://shipapi-sandbox.purolator.com', 'authScope' => 'portal_api_sandbox'],
        'prod' => ['baseUrl' => 'https://shipapi.purolator.com',         'authScope' => 'portal_api'],
    ];

    /** Collection variables with fixed defaults (same values as the Postman collections). */
    public const DEFAULTS = [
        'language'                 => 'en',
        'createShipmentPath'       => '/ship/v1/shipment',
        'rma'                      => '123456789012345',
        'pickupConfirmationNumber' => '',
        'trackingId'               => '',
        'shipmentReference'        => '',
        'city'                     => 'Toronto',
        'provinceCode'             => 'ON',
        'postalCode'               => 'M9A0A5',
    ];

    /** Purolator validates dates in its own time zone, not the runner's. */
    private const CARRIER_TZ = 'America/Toronto';

    /**
     * Return the definition when a script is included; run it when it is the
     * script PHP was started with.
     *
     * @param array<string,mixed> $def
     * @return array<string,mixed>
     */
    public static function runIfMain(array $def, string $file): array
    {
        $script = $_SERVER['SCRIPT_FILENAME'] ?? '';
        $isMain = PHP_SAPI === 'cli' && $script !== '' && realpath($script) === realpath($file);
        if (!$isMain) {
            return $def;
        }
        exit(self::run($def, array_slice($_SERVER['argv'] ?? [], 1)));
    }

    /**
     * Send one request definition. Returns a process exit code:
     * 0 = HTTP 2xx, 1 = other HTTP status, 2 = could not send (setup problem).
     *
     * @param array<string,mixed> $def
     * @param list<string>        $args  --dry-run (print only), --save (write full response to output/)
     */
    public static function run(array $def, array $args = []): int
    {
        $dry  = in_array('--dry-run', $args, true);
        $save = in_array('--save', $args, true);
        $root = dirname(__DIR__);

        try {
            self::loadEnvFile($root . '/.env');
            $tier  = self::tier();
            $state = self::readState($root);
            $st    = $state[$tier] ?? [];
            $dyn   = self::dynamicVars((int)($def['dateOffset'] ?? 0), isset($def['dateOffset']));
            $get   = self::lookup($tier, $dyn, $st);
            $strict = !$dry;   // a dry run may leave {{placeholders}} unresolved

            $url     = self::resolve((string)$def['url'], $get, $strict);
            $method  = (string)$def['method'];
            $headers = [];
            foreach (($def['headers'] ?? []) as $name => $value) {
                $headers[(string)$name] = self::resolve((string)$value, $get, $strict);
            }

            // Authorization: OAuth bearer token, or Basic for the token call itself.
            $auth = $def['auth'] ?? 'bearer';
            if ($auth === 'basic') {
                $user = self::resolve((string)($def['basic']['username'] ?? '{{REST_ClientId}}'), $get, $strict);
                $pass = self::resolve((string)($def['basic']['password'] ?? '{{REST_Secret}}'), $get, $strict);
                $headers['Authorization'] = 'Basic ' . base64_encode($user . ':' . $pass);
            } elseif ($auth === 'bearer') {
                $headers['Authorization'] = 'Bearer ' . ($dry ? '<token>' : self::bearerToken($root, $tier, $state));
            }

            $body = isset($def['body']) ? self::resolve((string)$def['body'], $get, $strict) : null;

            if ($dry) {
                self::printDryRun($def, $method, $url, $headers, $body);
                return 0;
            }

            fwrite(STDOUT, "{$method} {$url}\n");
            $res = self::send($method, $url, $headers, $body);
            fwrite(STDOUT, "HTTP {$res['status']}\n");

            $json = json_decode($res['body'], true);
            fwrite(STDOUT, self::pretty($json, $res['body']) . "\n");

            if ($save) {
                $dir = $root . '/output';
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                $file = $dir . '/' . self::slug((string)($def['name'] ?? 'response')) . '.json';
                file_put_contents($file, $res['body']);
                fwrite(STDOUT, "Saved full response to {$file}\n");
            }

            // Remember tokens and PINs for the requests that run after this one.
            if (is_array($json) && isset($def['capture'])) {
                self::capture($root, $tier, $def['capture'], $json);
            }

            return ($res['status'] >= 200 && $res['status'] < 300) ? 0 : 1;
        } catch (RuntimeException $e) {
            fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
            return 2;
        }
    }

    // ------------------------------------------------------------------ settings

    /** Load KEY=VALUE lines from .env without overriding real environment variables. */
    private static function loadEnvFile(string $file): void
    {
        if (!is_file($file)) {
            return;
        }
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v);
            // Strip one pair of matching quotes.
            if (strlen($v) >= 2 && ($v[0] === '"' || $v[0] === "'") && $v[-1] === $v[0]) {
                $v = substr($v, 1, -1);
            }
            if ($k !== '' && getenv($k) === false) {
                putenv("{$k}={$v}");
            }
        }
    }

    private static function tier(): string
    {
        $tier = strtolower((string)(getenv('PUROLATOR_ENV') ?: 'dev'));
        if (!isset(self::TIERS[$tier])) {
            throw new RuntimeException("PUROLATOR_ENV must be 'dev' or 'prod' (got '{$tier}').");
        }
        return $tier;
    }

    // ------------------------------------------------------------------ variables

    /**
     * Values the Postman collections set in their pre-request scripts.
     *
     * @return array<string,string>
     */
    private static function dynamicVars(int $shipDaysAhead, bool $hasOffset): array
    {
        $today = self::carrierDay(0);

        // Next business day for pickups: skip Saturday and Sunday.
        $pickupOffset = 1;
        while (in_array((int)self::carrierDate($pickupOffset)->format('w'), [0, 6], true)) {
            $pickupOffset++;
        }

        return [
            'Current_Date'          => $hasOffset ? self::carrierDay($shipDaysAhead) : $today,
            'RequestReference'      => 'REQ' . date('YmdHis'),
            'manifestDate'          => $today,
            'manifestDateOld'       => self::carrierDay(-11),  // just outside the 10-day window
            'manifestDateFuture'    => self::carrierDay(1),
            'shipmentDateFar'       => self::carrierDay(15),   // beyond the +10 day ship window
            'trackDateFrom'         => self::carrierDay(-30),
            'trackDateTo'           => $today,
            'pickupDate'            => self::carrierDay($pickupOffset),
            'pickupHistoryFromDate' => self::carrierDay(-30),
            'pickupHistoryToDate'   => $today,
        ];
    }

    private static function carrierDate(int $offsetDays): \DateTimeImmutable
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone(self::CARRIER_TZ));
        return $offsetDays === 0 ? $now : $now->modify(sprintf('%+d days', $offsetDays));
    }

    private static function carrierDay(int $offsetDays): string
    {
        return self::carrierDate($offsetDays)->format('Y-m-d');
    }

    /**
     * Build the {{variable}} lookup. Order: computed values, environment / .env,
     * values captured by earlier requests, tier settings, fixed defaults.
     *
     * @param array<string,string> $dyn
     * @param array<string,mixed>  $st
     */
    private static function lookup(string $tier, array $dyn, array $st): \Closure
    {
        $tierVars = self::TIERS[$tier] + ['rateBaseUrl' => self::TIERS[$tier]['baseUrl']];
        return static function (string $name) use ($dyn, $st, $tierVars): ?string {
            if (isset($dyn[$name])) {
                return $dyn[$name];
            }
            $env = getenv($name);
            if ($env !== false && $env !== '') {
                return $env;
            }
            if (isset($st[$name]) && $st[$name] !== '') {
                return (string)$st[$name];
            }
            if (isset($tierVars[$name])) {
                return $tierVars[$name];
            }
            return array_key_exists($name, self::DEFAULTS) ? self::DEFAULTS[$name] : null;
        };
    }

    private static function resolve(string $text, \Closure $get, bool $strict): string
    {
        return (string)preg_replace_callback('/\{\{([A-Za-z0-9_]+)\}\}/', static function (array $m) use ($get, $strict): string {
            $value = $get($m[1]);
            if ($value !== null) {
                return $value;
            }
            if (!$strict) {
                return $m[0];
            }
            $hint = str_starts_with($m[1], 'PIN')
                ? ' Run the shipment request that creates it first (scripts in the same folder, in order), or set it in .env.'
                : ' Set it as an environment variable or in .env (see .env.example).';
            throw new RuntimeException('No value for {{' . $m[1] . '}}.' . $hint);
        }, $text);
    }

    // ------------------------------------------------------------------ token + state

    /** @return array<string,mixed> */
    private static function readState(string $root): array
    {
        $file = $root . '/.state.json';
        $data = is_file($file) ? json_decode((string)file_get_contents($file), true) : null;
        return is_array($data) ? $data : [];
    }

    /** @param array<string,mixed> $state */
    private static function writeState(string $root, array $state): void
    {
        file_put_contents($root . '/.state.json', json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    /** @param array<string,mixed> $state */
    private static function bearerToken(string $root, string $tier, array $state): string
    {
        // A token supplied by hand wins.
        $manual = getenv('REST_Token');
        if ($manual !== false && $manual !== '') {
            return $manual;
        }
        // Otherwise reuse the cached one until shortly before it expires.
        $st = $state[$tier] ?? [];
        if (!empty($st['REST_Token']) && (int)($st['REST_Token_exp'] ?? 0) > time() + 30) {
            return (string)$st['REST_Token'];
        }

        $id     = getenv('REST_ClientId');
        $secret = getenv('REST_Secret');
        if (!$id || !$secret) {
            throw new RuntimeException('REST_ClientId and REST_Secret are needed to get a token. Set them in .env (see .env.example).');
        }
        $base = self::TIERS[$tier]['baseUrl'];
        $res  = self::send('POST', $base . '/auth/v1/token', [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($id . ':' . $secret),
        ], json_encode(['grant_type' => 'client_credentials', 'scope' => self::TIERS[$tier]['authScope']]));
        $json = json_decode($res['body'], true);
        if ($res['status'] !== 200 || !is_array($json) || empty($json['access_token'])) {
            throw new RuntimeException("Token request failed (HTTP {$res['status']}): " . substr($res['body'], 0, 300));
        }
        self::storeToken($root, $tier, $json);
        return (string)$json['access_token'];
    }

    /** @param array<string,mixed> $json */
    private static function storeToken(string $root, string $tier, array $json): void
    {
        $state = self::readState($root);
        $state[$tier]['REST_Token']     = (string)$json['access_token'];
        $state[$tier]['REST_Token_exp'] = time() + (int)($json['expires_in'] ?? 3600);
        self::writeState($root, $state);
    }

    /**
     * Save what a response gives later requests: the token, or a shipment PIN
     * stored under each variable name the request lists.
     *
     * @param array<string,mixed> $capture
     * @param array<mixed>        $json
     */
    private static function capture(string $root, string $tier, array $capture, array $json): void
    {
        if (!empty($capture['token']) && !empty($json['access_token'])) {
            self::storeToken($root, $tier, $json);
            fwrite(STDOUT, "Stored token for the {$tier} tier.\n");
            return;
        }
        $vars = $capture['vars'] ?? [];
        if (!$vars) {
            return;
        }
        // Most specific key first, so a piece PIN never wins over the shipment PIN.
        $pin = '';
        foreach (($capture['keys'] ?? ['shipmentpin']) as $key) {
            $pin = self::findValue($json, strtolower($key));
            if ($pin !== '') {
                break;
            }
        }
        if ($pin === '') {
            fwrite(STDOUT, 'No PIN in the response; ' . implode(', ', $vars) . " left unchanged.\n");
            return;
        }
        $state = self::readState($root);
        foreach ($vars as $v) {
            $state[$tier][$v] = $pin;
        }
        self::writeState($root, $state);
        fwrite(STDOUT, "Captured PIN {$pin} -> " . implode(', ', $vars) . "\n");
    }

    /**
     * Depth-first search for a key (case-insensitive). Accepts "123", 123 and
     * {"value": "123"}, the shapes the REST responses use for a PIN.
     *
     * @param array<mixed> $node
     */
    private static function findValue(array $node, string $wanted): string
    {
        foreach ($node as $key => $val) {
            if (is_string($key) && strtolower($key) === $wanted) {
                if (is_string($val) && trim($val) !== '') {
                    return trim($val);
                }
                if (is_int($val) || is_float($val)) {
                    return (string)$val;
                }
                if (is_array($val) && !empty($val['value'])) {
                    return trim((string)$val['value']);
                }
            }
            if (is_array($val)) {
                $hit = self::findValue($val, $wanted);
                if ($hit !== '') {
                    return $hit;
                }
            }
        }
        return '';
    }

    // ------------------------------------------------------------------ HTTP

    /**
     * One cURL call. Header names are sent exactly as written: the production
     * gateway authorizer is case-sensitive about "x-api-key", and some HTTP
     * libraries rewrite it to "X-Api-Key".
     *
     * @param array<string,string> $headers
     * @return array{status:int,body:string}
     */
    private static function send(string $method, string $url, array $headers, ?string $body): array
    {
        $lines = [];
        foreach ($headers as $name => $value) {
            $lines[] = $name . ': ' . $value;
        }
        $ch = curl_init($url);
        $opts = [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $lines,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT        => 60,
        ];
        if ($body !== null && $method !== 'GET') {
            $opts[CURLOPT_POSTFIELDS] = $body;
        }
        // PHP on Windows ships without a CA bundle. Point this at cacert.pem if TLS fails.
        $ca = getenv('PUROLATOR_CAINFO');
        if ($ca) {
            $opts[CURLOPT_CAINFO] = $ca;
        }
        curl_setopt_array($ch, $opts);
        $out = curl_exec($ch);
        if ($out === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('HTTP call failed: ' . $err);
        }
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return ['status' => $status, 'body' => (string)$out];
    }

    // ------------------------------------------------------------------ output

    /** @param array<string,string> $headers */
    private static function printDryRun(array $def, string $method, string $url, array $headers, ?string $body): void
    {
        fwrite(STDOUT, '# ' . ($def['folder'] ?? '') . ' / ' . ($def['name'] ?? '') . "\n");
        fwrite(STDOUT, "{$method} {$url}\n");
        foreach ($headers as $name => $value) {
            // Never print credentials.
            $secret = in_array(strtolower($name), ['authorization', 'x-api-key'], true);
            fwrite(STDOUT, $name . ': ' . ($secret ? '<hidden>' : $value) . "\n");
        }
        if ($body !== null) {
            $json = json_decode($body, true);
            fwrite(STDOUT, "\n" . (is_array($json) ? self::pretty($json, $body) : $body) . "\n");
        }
    }

    /** @param mixed $json */
    private static function pretty($json, string $raw): string
    {
        if (!is_array($json)) {
            return $raw;
        }
        return (string)json_encode(self::shorten($json), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** Cut very long strings (base64 labels) so the console stays readable. */
    private static function shorten(mixed $v): mixed
    {
        if (is_array($v)) {
            return array_map([self::class, 'shorten'], $v);
        }
        if (is_string($v) && strlen($v) > 300) {
            return substr($v, 0, 80) . '... [' . strlen($v) . ' chars, use --save for the full response]';
        }
        return $v;
    }

    private static function slug(string $text): string
    {
        $slug = trim((string)preg_replace('/[^A-Za-z0-9]+/', '-', $text), '-');
        return $slug !== '' ? $slug : 'response';
    }
}
