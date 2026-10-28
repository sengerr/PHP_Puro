<?php
/**
 * PurolatorClient -- a small, dependency-free client for the Purolator REST API.
 *
 * Plain PHP 8.1+ with the cURL extension. No Composer, no framework.
 *
 * Everything that identifies you comes from ENVIRONMENT VARIABLES, never from
 * the code, so these files can be shared and committed safely:
 *
 *   PUROLATOR_ENV            dev (default) or prod -- picks the host and OAuth scope
 *   PUROLATOR_CLIENT_ID      Okta client id      (token call, Basic auth user)
 *   PUROLATOR_CLIENT_SECRET  Okta client secret  (token call, Basic auth password)
 *   PUROLATOR_X_API_KEY      x-api-key header, required on every call
 *   PUROLATOR_ACCOUNT        your billing / registered account number
 *   PUROLATOR_ACCOUNT_2      optional second account (third-party / receiver billing)
 *   PUROLATOR_TOKEN          optional: a bearer token you already hold (skips the token call)
 *   PUROLATOR_LANGUAGE       optional: en (default) or fr
 *   PUROLATOR_SHIP_DAYS_AHEAD optional: shipment date offset for create / validate (default 5)
 *   PUROLATOR_VAR_<NAME>     optional: value for any other {{NAME}} placeholder in an
 *                            example body, e.g. PUROLATOR_VAR_PIN for {{PIN}}
 *
 * Hosts:
 *   dev  -> https://shipapi-sandbox.purolator.com  (scope portal_api_sandbox)
 *   prod -> https://shipapi.purolator.com          (scope portal_api)
 *
 * Production calls create real shipments, pickups and charges.
 */

declare(strict_types=1);

namespace PhpPuro;

use RuntimeException;

final class PurolatorClient
{
    /** Host and OAuth scope per tier. */
    private const TIERS = [
        'dev'  => ['https://shipapi-sandbox.purolator.com', 'portal_api_sandbox'],
        'prod' => ['https://shipapi.purolator.com', 'portal_api'],
    ];

    /** Seconds shaved off the token lifetime so a token is never used as it expires. */
    private const TOKEN_MARGIN = 60;

    private string $tier;
    private string $baseUrl;
    private string $scope;
    private string $apiKey;
    private ?string $token = null;
    private int $tokenExpires = 0;

    /**
     * Read the tier and the x-api-key from the environment.
     *
     * @param string|null $tier 'dev' or 'prod'; null reads PUROLATOR_ENV (default dev).
     */
    public function __construct(?string $tier = null)
    {
        $tier = strtolower($tier ?? (self::env('PUROLATOR_ENV') ?? 'dev'));
        if (!isset(self::TIERS[$tier])) {
            throw new RuntimeException("PUROLATOR_ENV must be 'dev' or 'prod', got '$tier'.");
        }
        $this->tier = $tier;
        [$this->baseUrl, $this->scope] = self::TIERS[$tier];
        $this->apiKey = self::requireEnv('PUROLATOR_X_API_KEY');
        // A token supplied by the caller is used as-is (no expiry known: assume one hour).
        $preset = self::env('PUROLATOR_TOKEN');
        if ($preset !== null) {
            $this->token = $preset;
            $this->tokenExpires = time() + 3600;
        }
    }

    /** The tier in use ('dev' or 'prod'). */
    public function tier(): string
    {
        return $this->tier;
    }

    /** The REST host for the tier in use. */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    /** The OAuth scope for the tier in use (portal_api_sandbox / portal_api). */
    public function scope(): string
    {
        return $this->scope;
    }

    // -------------------------------------------------------------------------
    // Authentication
    // -------------------------------------------------------------------------

    /**
     * Return a bearer token, fetching a new one from POST /auth/v1/token when needed.
     *
     * The token call uses HTTP Basic auth (client id : client secret) and the
     * body {"grant_type": "client_credentials", "scope": "<tier scope>"}.
     */
    public function token(bool $forceRefresh = false): string
    {
        if (!$forceRefresh && $this->token !== null && time() < $this->tokenExpires) {
            return $this->token;
        }
        $id = self::requireEnv('PUROLATOR_CLIENT_ID');
        $secret = self::requireEnv('PUROLATOR_CLIENT_SECRET');
        $res = $this->send('POST', '/auth/v1/token', [
            'Authorization: Basic ' . base64_encode($id . ':' . $secret),
            'x-api-key: ' . $this->apiKey,
            'Content-Type: application/json',
        ], json_encode(['grant_type' => 'client_credentials', 'scope' => $this->scope]));
        $json = $res['json'];
        if ($res['status'] !== 200 || !is_array($json) || empty($json['access_token'])) {
            throw new RuntimeException('Token request failed (HTTP ' . $res['status'] . '): ' . $res['body']);
        }
        $this->token = (string) $json['access_token'];
        // expires_in is in seconds; keep a safety margin.
        $this->tokenExpires = time() + max(0, (int) ($json['expires_in'] ?? 3600) - self::TOKEN_MARGIN);
        return $this->token;
    }

    // -------------------------------------------------------------------------
    // Requests
    // -------------------------------------------------------------------------

    /**
     * Send one API call with the standard Purolator headers.
     *
     * @param string            $method  GET, POST or PUT
     * @param string            $path    e.g. /ship/v1/shipment
     * @param array|string|null $body    array (JSON-encoded here), raw JSON string, or null
     * @param array             $query   query-string parameters (the locator is a GET)
     * @param array             $headers extra "Name: value" headers
     * @return array{status:int, json:mixed, body:string, headers:array}
     */
    public function request(string $method, string $path, array|string|null $body = null,
                            array $query = [], array $headers = []): array
    {
        $std = [
            'Authorization: Bearer ' . $this->token(),
            'x-api-key: ' . $this->apiKey,
            // RequestReference echoes back in the response; the spec allows 36 chars.
            'RequestReference: ' . self::requestReference(),
            'Language: ' . self::language(),
        ];
        if ($body !== null) {
            $std[] = 'Content-Type: application/json';
            if (is_array($body)) {
                $body = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
        // A header the caller passes (e.g. "Language: fr") replaces the standard one.
        $override = array_map(fn ($h) => strtolower(trim(explode(':', $h, 2)[0])), $headers);
        $std = array_values(array_filter($std, fn ($h) => !in_array(strtolower(explode(':', $h, 2)[0]), $override, true)));
        if ($query) {
            $path .= '?' . http_build_query($query);
        }
        $res = $this->send($method, $path, array_merge($std, $headers), $body);
        // One retry with a fresh token if the cached one was rejected.
        if ($res['status'] === 401 && self::env('PUROLATOR_TOKEN') === null) {
            $this->token(true);
            // The Authorization header is always first in $std (never overridden).
            $std[0] = 'Authorization: Bearer ' . $this->token();
            $res = $this->send($method, $path, array_merge($std, $headers), $body);
        }
        return $res;
    }

    /**
     * Fill {{NAME}} placeholders in a JSON template (the example bodies).
     *
     * Values come from, in order: $vars, the built-in names (AccountNumber,
     * Different_AccountNumber, Current_Date, RequestReference, language,
     * manifestDate), then PUROLATOR_VAR_<NAME>. A placeholder with no value is
     * an error that names the variable to set.
     *
     * @param bool $isShipment true for create / validate: Current_Date becomes
     *                         today + PUROLATOR_SHIP_DAYS_AHEAD (default 5).
     */
    public static function fill(string $template, array $vars = [], bool $isShipment = false): string
    {
        $missing = [];
        $out = preg_replace_callback('/\{\{([A-Za-z0-9_]+)\}\}/', function (array $m) use ($vars, $isShipment, &$missing) {
            $value = $vars[$m[1]] ?? self::builtin($m[1], $isShipment) ?? self::env('PUROLATOR_VAR_' . $m[1]);
            if ($value === null) {
                $missing[] = $m[1];
                return $m[0];
            }
            return (string) $value;
        }, $template);
        if ($missing) {
            $names = array_unique($missing);
            throw new RuntimeException('Set a value for: ' . implode(', ', array_map(
                fn ($n) => "PUROLATOR_VAR_$n", $names)) . ' (or pass it as NAME=value on the command line).');
        }
        return $out;
    }

    /** Values for the placeholders every example shares; null when not built in. */
    private static function builtin(string $name, bool $isShipment): ?string
    {
        return match ($name) {
            'AccountNumber'           => self::requireEnv('PUROLATOR_ACCOUNT'),
            'Different_AccountNumber' => self::env('PUROLATOR_ACCOUNT_2'),
            'Current_Date'            => self::shipmentDate($isShipment ? (int) (self::env('PUROLATOR_SHIP_DAYS_AHEAD') ?? '5') : 0),
            // Summary manifest dates must fall in today-10 .. today; default yesterday.
            'manifestDate'            => self::shipmentDate(-1),
            'RequestReference'        => self::requestReference(),
            'language'                => self::language(),
            default                   => null,
        };
    }

    /** Parse NAME=value command-line arguments into a placeholder map. */
    public static function argVars(array $argv): array
    {
        $vars = [];
        foreach (array_slice($argv, 1) as $arg) {
            if (str_contains($arg, '=')) {
                [$k, $v] = explode('=', $arg, 2);
                $vars[$k] = $v;
            }
        }
        return $vars;
    }

    /**
     * A date in Purolator's timezone (America/Toronto), YYYY-MM-DD.
     * Using the machine's own date sends "yesterday" late in the day west of
     * Eastern time, which the API rejects (9001-11).
     */
    public static function shipmentDate(int $daysAhead = 0): string
    {
        $d = new \DateTimeImmutable('now', new \DateTimeZone('America/Toronto'));
        return $d->modify(($daysAhead >= 0 ? '+' : '') . $daysAhead . ' days')->format('Y-m-d');
    }

    /** "REQ" + YYYYMMDDHHMMSS -- the same convention as the Postman collections. */
    public static function requestReference(): string
    {
        return 'REQ' . (new \DateTimeImmutable('now', new \DateTimeZone('America/Toronto')))->format('YmdHis');
    }

    /** Response language: PUROLATOR_LANGUAGE or en. */
    public static function language(): string
    {
        return strtolower(self::env('PUROLATOR_LANGUAGE') ?? 'en');
    }

    // -------------------------------------------------------------------------
    // Output helper for the examples
    // -------------------------------------------------------------------------

    /** Print a response: status line, then pretty JSON (or the raw body). */
    public static function show(array $res): void
    {
        echo 'HTTP ', $res['status'], PHP_EOL;
        echo is_array($res['json'])
            ? json_encode($res['json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            : $res['body'];
        echo PHP_EOL;
    }

    // -------------------------------------------------------------------------
    // Internals
    // -------------------------------------------------------------------------

    /**
     * One cURL round trip.
     *
     * @return array{status:int, json:mixed, body:string, headers:array}
     */
    private function send(string $method, string $path, array $headers, ?string $body): array
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('The PHP cURL extension is not enabled (php.ini: extension=curl).');
        }
        $respHeaders = [];
        $ch = curl_init($this->baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            // Collect response headers (RequestReference, Language come back here).
            CURLOPT_HEADERFUNCTION => function ($c, string $line) use (&$respHeaders) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $respHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
                return strlen($line);
            },
        ]);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        $raw = curl_exec($ch);
        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("cURL error calling $method $path: $err");
        }
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        // Bodies are JSON; anything else is returned raw with json = null.
        $json = json_decode((string) $raw, true);
        return ['status' => $status, 'json' => $json, 'body' => (string) $raw, 'headers' => $respHeaders];
    }

    /** An environment variable, or null when unset or empty. */
    private static function env(string $name): ?string
    {
        $v = getenv($name);
        return ($v === false || $v === '') ? null : $v;
    }

    /** An environment variable that must be set. */
    private static function requireEnv(string $name): string
    {
        $v = self::env($name);
        if ($v === null) {
            throw new RuntimeException("Environment variable $name is not set. See README.md.");
        }
        return $v;
    }
}
