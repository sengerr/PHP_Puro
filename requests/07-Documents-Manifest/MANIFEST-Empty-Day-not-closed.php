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

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /ship/v1/documents/summary-manifest` -- Get Summary Manifest spec (GetSummaryManifestRequest). Get summary manifest

Retrieve summary manifest artifacts for one or more manifest dates (inclusive window of today minus 10 days through today). Freight is not supported.

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36

**Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `manifestDate`

**Fields in this body** (type, limits, accepted values; * = required):
- `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
- `manifestDate`* -- array, 1-n items. Manifest dates in YYYY-MM-DD. Each date must fall in the inclusive window from today minus 10 days through today.
- `manifestDate[]` -- string (date)

**Conditional rules stated in the spec** (fields not sent here):
- `requestReference` -- A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
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
