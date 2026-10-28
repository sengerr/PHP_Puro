<?php
/**
 * TRACK · By shipmentPIN
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * Happy path. Run a Create first (02/03/07) so {{shipmentPIN}} is filled. Body fields from TrackingSearchCriteria / OnlineTrackingSearchRequest (OAS).
 *
 * Source: Purolator-REST-API-STG collection, "Track - happy by shipmentPIN". REST only (no SOAP twin, 2026-10-06).
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
 * - `search[].pod` -- boolean
 * - `search[].eventSortOrder` -- string
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-by-shipmentpin.php [NAME=value ...]
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
            "trackingId": "{{PIN}}",
            "pod": false,
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
