<?php
/**
 * TRACK · Last Event
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * **Spec-aligned 2026-10-24, not yet verified live** -- changed to match the published OpenAPI spec after it failed (or was not sent) in the 2026-10-06 run.
 *
 * Converted from SOAP TrackingByPinsOrReferencesRequest — redactable sample “SOAP-Tracking- Last Event.txt”.
 *
 * SOAP v2 tracking already uses the REST search field names — mapped through directly.
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
 * - `search[].destinationPostalZipCode` -- string
 * - `search[].eventSortOrder` -- string
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-last-event.php [NAME=value ...]
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
    "language": "{{language}}",
    "search": [
        {
            "trackingId": "{{PIN}}",
            "account": "{{AccountNumber}}",
            "destinationPostalZipCode": "T2E8Z9",
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
