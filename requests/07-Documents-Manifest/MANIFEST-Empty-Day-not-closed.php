<?php
/**
 * 07 · Documents & Manifest / MANIFEST · Empty Day (not closed)
 * POST {{baseUrl}}/ship/v1/documents/summary-manifest
 *
 * Run:  php requests/07-Documents-Manifest/MANIFEST-Empty-Day-not-closed.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'MANIFEST · Empty Day (not closed)',
    'order'  => 953700,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents/summary-manifest',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Support coaching: empty/missing batch for a valid in-window date usually means Close Manifest was never run - not necessarily an HTTP error. Observe body; do not invent status.

Source: Purolator-REST-API-STG collection, "POST Summary Manifest - empty results day (not closed)". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "manifestDate": [
        "{{manifestDate}}"
    ]
}
PURO_BODY,
], __FILE__);
