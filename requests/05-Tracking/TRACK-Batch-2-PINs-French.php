<?php
/**
 * 05 · Tracking / TRACK · Batch 2 PINs French
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-Batch-2-PINs-French.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK · Batch 2 PINs French',
    'order'  => 956500,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
search is an array: several PINs/references in one call. language drives customer facing fields. Fill {{shipmentPIN2}} manually.

Source: Purolator-REST-API-STG collection, "Track - batch 2 PINs, French language". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "fr",
    "search": [
        {
            "trackingId": "{{PIN}}"
        },
        {
            "trackingId": "{{PIN2}}"
        }
    ]
}
PURO_BODY,
], __FILE__);
