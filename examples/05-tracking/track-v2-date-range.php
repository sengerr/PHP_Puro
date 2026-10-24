<?php
/**
 * TRACK V2 · Date range
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * REST twin of the SOAP request of the same name (converted with tooling/generators). ShipmentTrackingServiceEndpoint.TrackingByPinsOrReferences — v2. Bounds the search by shipment date. trackingDateFrom is set by this request's pre-request script to 30 days back, so the window moves with the run rather than going stale like a literal date would. v2 ShipmentTracking exposes this single operation; v1's TrackPackagesByPin, TrackPackagesByReference and GetDeliveryDetails are all folded into it. SOAP v2 tracking already uses the REST search field names — mapped through directly.
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
 * - `search[].shipmentDateFrom` -- string
 * - `search[].shipmentDateTo` -- string
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-v2-date-range.php [NAME=value ...]
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
            "trackingId": "{{trackingId}}",
            "shipmentDateFrom": "{{trackDateFrom}}",
            "shipmentDateTo": "{{Current_Date}}"
        }
    ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/track/v1/shipment', $body, $query, $headers));
