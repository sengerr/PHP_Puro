<?php
/**
 * 01 · Estimate / QUICK ESTIMATE · International · Basic
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/QUICK-ESTIMATE-International-Basic.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'QUICK ESTIMATE · International · Basic',
    'order'  => 18000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/rate/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Converted from SOAP EstimatingServiceEndpoint.GetQuickEstimate — test-aid case “02. International shipment”.

Quick Estimate has no dedicated REST endpoint — mapped to a minimal /rate/v1/shipment call. SOAP PackageType has no REST field and was dropped.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "outboundShipment": {
        "billingInformation": {
            "billingAccountNumber": "{{AccountNumber}}",
            "displayPublishedRates": false
        },
        "senderInformation": {
            "streetAddress": [
                "3700 Jericho Road"
            ],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5"
        },
        "receiverInformation": {
            "city": "Ottobrunn",
            "country": "DE",
            "postalZipCode": "85501"
        },
        "shipmentInformation": {
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 10,
            "totalPackages": 1,
            "showAlternativeServicesIndicator": true,
            "serviceId": "PurolatorExpressInternational"
        }
    }
}
PURO_BODY,
], __FILE__);
