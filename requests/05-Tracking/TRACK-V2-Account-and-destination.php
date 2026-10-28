<?php
/**
 * 05 · Tracking / TRACK V2 · Account and destination
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-V2-Account-and-destination.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK V2 · Account and destination',
    'order'  => 10000,
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
REST twin of the SOAP request of the same name (converted with tooling/generators). ShipmentTrackingServiceEndpoint.TrackingByPinsOrReferences — v2. Narrows the search by account and destination postal code -- the fields that let a caller track a PIN they do not own. v2 ShipmentTracking exposes this single operation; v1's TrackPackagesByPin, TrackPackagesByReference and GetDeliveryDetails are all folded into it. SOAP v2 tracking already uses the REST search field names — mapped through directly.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "search": [
        {
            "trackingId": "{{trackingId}}",
            "account": "{{AccountNumber}}",
            "destinationPostalZipCode": "T2E8Z9"
        }
    ]
}
PURO_BODY,
], __FILE__);
