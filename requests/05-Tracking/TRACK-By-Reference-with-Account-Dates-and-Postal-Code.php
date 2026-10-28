<?php
/**
 * 05 · Tracking / TRACK · By Reference with Account Dates and Postal Code
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-By-Reference-with-Account-Dates-and-Postal-Code.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK · By Reference with Account Dates and Postal Code',
    'order'  => 956400,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
trackingId accepts a PIN or a reference. account, shipmentDateFrom/To and destinationPostalZipCode narrow duplicates (OAS). L5B 2C9 = receiver postal code of the domestic Create bodies.

Source: Purolator-REST-API-STG collection, "Track - by reference with account, date window and destination postal code". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "en",
    "search": [
        {
            "trackingId": "{{trackReference}}",
            "account": "{{AccountNumber}}",
            "shipmentDateFrom": "{{trackDateFrom}}",
            "shipmentDateTo": "{{trackDateTo}}",
            "destinationPostalZipCode": "L5B 2C9",
            "eventSortOrder": "d"
        }
    ]
}
PURO_BODY,
], __FILE__);
