<?php
/**
 * 01 · Estimate / SAMPLE · Domestic · Estimate Express 1030AM
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/SAMPLE-Domestic-Estimate-Express-1030AM.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'SAMPLE · Domestic · Estimate Express 1030AM',
    'order'  => 911800,
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
Team sample “Estimate” — sources/reference-collections/Purolator-Estimate.postman_collection.json (REST, PurolatorExpress10:30AM, billingInformation + Chain of Signature option). Naming: SAMPLE · Lane · Focus. Not verified live.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "shipmentDate": "{{Current_Date}}",
    "lineOfBusiness": "COURIER",
    "outboundShipment": {
        "billingInformation": {
            "billingAccountNumber": "{{AccountNumber}}",
            "displayPublishedRates": false
        },
        "senderInformation": {
            "companyName": "Purolator",
            "streetAddress": ["17718 114 Ave"],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4"
        },
        "receiverInformation": {
            "companyName": "Purolator",
            "streetAddress": ["30 Aero Drive NE"],
            "city": "Calgary",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T2E8Z9"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress10:30AM",
            "unitOfMeasurement": null,
            "showAlternativeServicesIndicator": false,
            "totalWeight": 1,
            "totalPackages": 0,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ChainOfSignature",
                    "optionIdValue": "false"
                }
            ]
        }
    }
}
PURO_BODY,
], __FILE__);
