<?php
/**
 * MANIFEST · Shipment Manifest Document
 *
 * POST /ship/v1/documents/summary-manifest   (folder: 07 · Documents & Manifest)
 *
 * Converted from SOAP ShippingDocumentsServiceEndpoint.GetShipmentManifestDocument — test-aid case “08. Shipment Manifest Document”.
 *
 * Path and body confirmed from the UAT sample collection. REST takes manifestDate as an array.
 * Corrected 2026-10-06: summary manifest is POST /ship/v1/documents/summary-manifest with manifestDate as an array of dates within today-10 .. today (Purolator REST STG collection, OAS).
 *
 * ### Developer notes
 * `POST /ship/v1/documents/summary-manifest` -- Get Summary Manifest spec (GetSummaryManifestRequest). Get summary manifest
 *
 * Retrieve summary manifest artifacts for one or more manifest dates (inclusive window of today minus 10 days through today). Freight is not supported.
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36
 *
 * **Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `manifestDate`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
 * - `manifestDate`* -- array, 1-n items. Manifest dates in YYYY-MM-DD. Each date must fall in the inclusive window from today minus 10 days through today.
 * - `manifestDate[]` -- string (date)
 * - `requestReference` -- string, max 36 chars. A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/07-documents-and-manifest/manifest-shipment-manifest-document.php [NAME=value ...]
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
    "lineOfBusiness": "Courier",
    "manifestDate": [
        "{{manifestDate}}"
    ],
    "requestReference": "{{RequestReference}}"
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/documents/summary-manifest', $body, $query, $headers));
