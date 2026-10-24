<?php
/**
 * VOID SHIP · By PIN
 *
 * PUT /ship/v1/shipment   (folder: 02 · Shipping)
 *
 * PUT void using shipmentPIN from a prior successful create (collection var).
 *
 * Source: Purolator-REST-API-STG collection, "Void Shipment by PIN". REST only (no SOAP twin, 2026-10-06).
 *
 * ### Developer notes
 * `PUT /ship/v1/shipment` -- Create / Void Shipment spec (VoidShipmentRequest). Void a shipment
 *
 * Void a previously created courier shipment by PIN. Same resource path as Create Shipment; this operation is PUT. Freight is not supported.
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36
 *
 * **Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `shipmentPIN`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
 * - `shipmentPIN`* -- string
 *
 * **Other accepted values you can send here:**
 * - `language` -- `EN` | `FR`
 *
 * **Conditional rules stated in the spec** (fields not sent here):
 * - `requestReference` -- A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
 * - `language` -- Response language. Case-insensitive. If not provided, defaults to EN (English).
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/02-shipping/void-ship-by-pin.php [NAME=value ...]
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
    "shipmentPIN": "{{PIN_VOID_1}}"
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('PUT', '/ship/v1/shipment', $body, $query, $headers));
