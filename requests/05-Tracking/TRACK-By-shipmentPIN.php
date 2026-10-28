<?php
/**
 * 05 · Tracking / TRACK · By shipmentPIN
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-By-shipmentPIN.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK · By shipmentPIN',
    'order'  => 956200,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Happy path. Run a Create first (02/03/07) so {{shipmentPIN}} is filled. Body fields from TrackingSearchCriteria / OnlineTrackingSearchRequest (OAS).

Source: Purolator-REST-API-STG collection, "Track - happy by shipmentPIN". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "en",
    "search": [
        {
            "trackingId": "{{PIN}}",
            "pod": false,
            "eventSortOrder": "d"
        }
    ]
}
PURO_BODY,
], __FILE__);
