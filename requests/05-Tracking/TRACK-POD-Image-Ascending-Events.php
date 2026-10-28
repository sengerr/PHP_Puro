<?php
/**
 * 05 · Tracking / TRACK · POD Image Ascending Events
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-POD-Image-Ascending-Events.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK · POD Image Ascending Events',
    'order'  => 956300,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
pod=true asks for proof of delivery image (signature/scanner photo) in package.details.deliveryDetails.image. Only populated once delivered. eventSortOrder a = ascending.

Source: Purolator-REST-API-STG collection, "Track - with POD image and ascending events". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
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
PURO_BODY,
], __FILE__);
