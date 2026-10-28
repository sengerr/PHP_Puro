<?php
/**
 * 05 · Tracking / TRACK V2 · Date range
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-V2-Date-range.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK V2 · Date range',
    'order'  => 9000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/track/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
REST twin of the SOAP request of the same name (converted with tooling/generators). ShipmentTrackingServiceEndpoint.TrackingByPinsOrReferences — v2. Bounds the search by shipment date. trackingDateFrom is set by this request's pre-request script to 30 days back, so the window moves with the run rather than going stale like a literal date would. v2 ShipmentTracking exposes this single operation; v1's TrackPackagesByPin, TrackPackagesByReference and GetDeliveryDetails are all folded into it. SOAP v2 tracking already uses the REST search field names — mapped through directly.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "search": [
        {
            "trackingId": "{{trackingId}}",
            "shipmentDateFrom": "{{trackDateFrom}}",
            "shipmentDateTo": "{{Current_Date}}"
        }
    ]
}
PURO_BODY,
], __FILE__);
