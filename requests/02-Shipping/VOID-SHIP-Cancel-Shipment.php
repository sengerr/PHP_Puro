<?php
/**
 * 02 · Shipping / VOID SHIP · Cancel Shipment
 * PUT {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/VOID-SHIP-Cancel-Shipment.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'VOID SHIP · Cancel Shipment',
    'order'  => 960000,
    'method' => 'PUT',
    'url'    => '{{baseUrl}}/ship/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
REST twin of the SOAP request of the same name (added 2026-10-06). Void is PUT /ship/v1/shipment with {lineOfBusiness, shipmentPIN} -- a PUT, not a POST (Purolator REST STG collection and support cheat sheet). Voids the shipment in {{PIN_VOID_3}}.

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`PUT /ship/v1/shipment` -- Create / Void Shipment spec (VoidShipmentRequest). Void a shipment

Void a previously created courier shipment by PIN. Same resource path as Create Shipment; this operation is PUT. Freight is not supported.

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36

**Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `shipmentPIN`

**Fields in this body** (type, limits, accepted values; * = required):
- `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
- `shipmentPIN`* -- string

**Other accepted values you can send here:**
- `language` -- `EN` | `FR`

**Conditional rules stated in the spec** (fields not sent here):
- `requestReference` -- A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
- `language` -- Response language. Case-insensitive. If not provided, defaults to EN (English).

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentPIN": "{{PIN_VOID_3}}"
}
PURO_BODY,
], __FILE__);
