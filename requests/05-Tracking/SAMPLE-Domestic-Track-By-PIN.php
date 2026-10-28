<?php
/**
 * 05 · Tracking / SAMPLE · Domestic · Track By PIN
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/SAMPLE-Domestic-Track-By-PIN.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'SAMPLE · Domestic · Track By PIN',
    'order'  => 904500,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Team sample “TrackPackagesByPinV1” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP TrackPackagesByPinRequest.

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /track/v1/shipment` -- Tracking spec (TrackingSearchCriteria). search by tracking id or reference

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required)

**Required fields** (top level, and inside every object this body sends): `search[].trackingId`

**Fields in this body** (type, limits, accepted values; * = required):
- `language` -- string
- `search` -- array
- `search[].trackingId`* -- string
- `search[].pod` -- boolean

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "search": [
        {
            "trackingId": "{{PIN}}",
            "pod": false
        }
    ]
}
PURO_BODY,
], __FILE__);
