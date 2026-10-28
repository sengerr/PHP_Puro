<?php
/**
 * 05 · Tracking / TRACK · By Reference with Account Dates and Postal Code
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-By-Reference-with-Account-Dates-and-Postal-Code.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK · By Reference with Account Dates and Postal Code',
    'order'  => 956400,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
trackingId accepts a PIN or a reference. account, shipmentDateFrom/To and destinationPostalZipCode narrow duplicates (OAS). L5B 2C9 = receiver postal code of the domestic Create bodies.

Source: Purolator-REST-API-STG collection, "Track - by reference with account, date window and destination postal code". REST only (no SOAP twin, 2026-10-06).

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /track/v1/shipment` -- Tracking spec (TrackingSearchCriteria). search by tracking id or reference

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required)

**Required fields** (top level, and inside every object this body sends): `search[].trackingId`

**Fields in this body** (type, limits, accepted values; * = required):
- `language` -- string
- `search` -- array
- `search[].trackingId`* -- string
- `search[].account` -- string
- `search[].shipmentDateFrom` -- string
- `search[].shipmentDateTo` -- string
- `search[].destinationPostalZipCode` -- string
- `search[].eventSortOrder` -- string

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "en",
    "search": [
        {
            "trackingId": "{{trackReference}}",
            "account": "{{AccountNumber}}",
            "shipmentDateFrom": "{{trackDateFrom}}",
            "shipmentDateTo": "{{trackDateTo}}",
            "destinationPostalZipCode": "L5B 2C9",
            "eventSortOrder": "d"
        }
    ]
}
PURO_BODY,
], __FILE__);
