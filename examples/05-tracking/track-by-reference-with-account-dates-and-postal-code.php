<?php
/**
 * TRACK · By Reference with Account Dates and Postal Code
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * trackingId accepts a PIN or a reference. account, shipmentDateFrom/To and destinationPostalZipCode narrow duplicates (OAS). L5B 2C9 = receiver postal code of the domestic Create bodies.
 *
 * Source: Purolator-REST-API-STG collection, "Track - by reference with account, date window and destination postal code". REST only (no SOAP twin, 2026-10-06).
 *
 * ### Developer notes
 * `POST /track/v1/shipment` -- Tracking spec (TrackingSearchCriteria). search by tracking id or reference
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required)
 *
 * **Required fields** (top level, and inside every object this body sends): `search[].trackingId`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `language` -- string
 * - `search` -- array
 * - `search[].trackingId`* -- string
 * - `search[].account` -- string
 * - `search[].shipmentDateFrom` -- string
 * - `search[].shipmentDateTo` -- string
 * - `search[].destinationPostalZipCode` -- string
 * - `search[].eventSortOrder` -- string
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-by-reference-with-account-dates-and-postal-code.php [NAME=value ...]
 * Tier: PUROLATOR_ENV=dev (default) or prod -- see README.md for every variable.
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

// Sample values from the Postman collection; override with NAME=value or PUROLATOR_VAR_<NAME>.
$defaults = [];
$vars = array_merge($defaults, PurolatorClient::argVars($argv));

// Request body exactly as in the collection; {{placeholders}} are filled below.
$template = <<<'JSON_BODY'
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
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/track/v1/shipment', $body, $query, $headers));
