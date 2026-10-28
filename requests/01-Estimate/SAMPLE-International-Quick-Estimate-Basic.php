<?php
/**
 * 01 · Estimate / SAMPLE · International · Quick Estimate Basic
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/SAMPLE-International-Quick-Estimate-Basic.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'SAMPLE · International · Quick Estimate Basic',
    'order'  => 902500,
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
Team sample “GetQuickEstimate - International Shipment” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP GetQuickEstimateRequest. Quick Estimate has no dedicated REST endpoint — mapped to a minimal /rate/v1/shipment call.
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
            "streetAddress": ["3700 Jericho Road"],
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
