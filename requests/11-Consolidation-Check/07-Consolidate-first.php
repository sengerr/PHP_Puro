<?php
/**
 * 11 · Consolidation Check / 07 · Consolidate (first)
 * POST {{baseUrl}}/ship/v1/manifest
 *
 * Run:  php requests/11-Consolidation-Check/07-Consolidate-first.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '07 · Consolidate (first)',
    'order'  => 7000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/manifest',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Consolidation check step 7 of 10.
Converted from the SOAP sample "consolidate.txt". Closes the manifest
  for everything created above. REST has no published consolidate spec;
  /ship/v1/manifest is a behavioural match - see docs/not-converted.md.

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /ship/v1/manifest` -- Close Manifest spec (CloseManifestRequest). Close manifest

Trigger end-of-day close (consolidate) of the courier manifest for an account. Freight is not supported.

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36

**Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `accountNumber`

**Fields in this body** (type, limits, accepted values; * = required):
- `language` -- string, default EN; accepted: `EN` | `FR`. Response language. Case-insensitive. If not provided, defaults to EN (English).
- `requestReference` -- string, max 36 chars. A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
- `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
- `accountNumber`* -- string, length 1-15

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "requestReference": "{{RequestReference}}",
    "lineOfBusiness": "Courier",
    "accountNumber": "{{AccountNumber}}"
}
PURO_BODY,
], __FILE__);
