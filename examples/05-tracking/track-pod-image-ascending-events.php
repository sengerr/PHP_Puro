<?php
/**
 * TRACK · POD Image Ascending Events
 *
 * POST /track/v1/shipment   (folder: 05 · Tracking)
 *
 * pod=true asks for proof of delivery image (signature/scanner photo) in package.details.deliveryDetails.image. Only populated once delivered. eventSortOrder a = ascending.
 *
 * Source: Purolator-REST-API-STG collection, "Track - with POD image and ascending events". REST only (no SOAP twin, 2026-10-06).
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
 * Run:  php examples/05-tracking/track-pod-image-ascending-events.php [NAME=value ...]
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
            "pod": true,
            "eventSortOrder": "a"
        }
    ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/track/v1/shipment', $body, $query, $headers));
