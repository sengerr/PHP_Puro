<?php
/**
 * TRACK V2 · Account and destination
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * REST twin of the SOAP request of the same name (converted with tooling/generators). ShipmentTrackingServiceEndpoint.TrackingByPinsOrReferences — v2. Narrows the search by account and destination postal code -- the fields that let a caller track a PIN they do not own. v2 ShipmentTracking exposes this single operation; v1's TrackPackagesByPin, TrackPackagesByReference and GetDeliveryDetails are all folded into it. SOAP v2 tracking already uses the REST search field names — mapped through directly.
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
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/05-tracking/track-v2-account-and-destination.php [NAME=value ...]
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
            "account": "{{AccountNumber}}",
            "destinationPostalZipCode": "T2E8Z9"
        }
    ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/track/v1/shipment', $body, $query, $headers));
