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
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentPIN": "{{PIN_VOID_3}}"
}
PURO_BODY,
], __FILE__);
