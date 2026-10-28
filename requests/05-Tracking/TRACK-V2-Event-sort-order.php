<?php
/**
 * 05 · Tracking / TRACK V2 · Event sort order
 * POST {{baseUrl}}/track/v1/shipment
 *
 * Run:  php requests/05-Tracking/TRACK-V2-Event-sort-order.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '05 · Tracking',
    'name'   => 'TRACK V2 · Event sort order',
    'order'  => 11000,
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
REST twin of the SOAP request of the same name (converted with tooling/generators). ShipmentTrackingServiceEndpoint.TrackingByPinsOrReferences — v2. eventSortOrder is a free string in the WSDL with no enumeration, so the accepted values are not published. “Descending” is the plausible reading and this request is how we find out -- if it is rejected, the error names the domain. v2 ShipmentTracking exposes this single operation; v1's TrackPackagesByPin, TrackPackagesByReference and GetDeliveryDetails are all folded into it. SOAP v2 tracking already uses the REST search field names — mapped through directly.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "search": [
        {
            "trackingId": "{{trackingId}}",
            "eventSortOrder": "d"
        }
    ]
}
PURO_BODY,
], __FILE__);
