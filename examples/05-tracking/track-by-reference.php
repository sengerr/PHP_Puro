<?php
/**
 * TRACK · By Reference
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * **Spec-aligned 2026-10-24, not yet verified live** -- changed to match the published OpenAPI spec after it failed (or was not sent) in the 2026-10-06 run.
 *
 * Converted from SOAP TrackingServiceEndpoint.TrackPackagesByReference — test-aid case “03.Track your reference”.
 *
 * CONFIRM: the published example only spells out trackingId; the reference field name should be checked against the Tracking OpenAPI document.
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
 * - `search[].account` -- string
 * - `search[].trackingId`* -- string
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-by-reference.php [NAME=value ...]
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
            "account": "{{AccountNumber}}",
            "trackingId": "{{shipmentReference}}"
        }
    ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/track/v1/shipment', $body, $query, $headers));
