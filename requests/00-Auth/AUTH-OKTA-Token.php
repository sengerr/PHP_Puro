<?php
/**
 * 00 · Auth / AUTH · OKTA Token
 * POST {{baseUrl}}/auth/v1/token
 *
 * Run:  php requests/00-Auth/AUTH-OKTA-Token.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '00 · Auth',
    'name'   => 'AUTH · OKTA Token',
    'order'  => 1000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/auth/v1/token',
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'basic',
    'basic'  => ['username' => '{{REST_ClientId}}', 'password' => '{{REST_Secret}}'],
    'capture' => ['token' => true],
    'description' => <<<'PURO_TEXT'
OAuth 2.0 client-credentials grant (Okta). Basic auth = client_id : client_secret.

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /auth/v1/token` -- Authentication (token) spec. Get

**Headers:** Authorization Basic `client_id:client_secret`

**Required fields** (top level, and inside every object this body sends): `grant_type`, `scope`

**Fields in this body** (type, limits, accepted values; * = required):
- `grant_type`* -- string
- `scope`* -- string

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "grant_type": "client_credentials",
    "scope": "{{authScope}}"
}
PURO_BODY,
], __FILE__);
