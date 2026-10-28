<?php
/**
 * 02 · Shipping / VOID SHIP · Alternate shipmentPin Key
 * PUT {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/VOID-SHIP-Alternate-shipmentPin-Key.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'VOID SHIP · Alternate shipmentPin Key',
    'order'  => 952100,
    'method' => 'PUT',
    'url'    => '{{baseUrl}}/ship/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Swagger notes shipmentPin (camel alternate) is also accepted.

Source: Purolator-REST-API-STG collection, "Void Shipment alternate shipmentPin key". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentPin": "{{PIN_VOID_2}}"
}
PURO_BODY,
], __FILE__);
