<?php
/**
 * 01 · Estimate / SAMPLE · International · Estimate Spain
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/SAMPLE-International-Estimate-Spain.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'SAMPLE · International · Estimate Spain',
    'order'  => 911200,
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
Team sample — sources/redactable-samples/international estimate/Spain Estimate.txt.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP GetFullEstimateRequest.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "outboundShipment": {
        "senderInformation": {
            "companyName": "Purolator",
            "streetAddress": [
                "3700 Jericho Road"
            ],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5"
        },
        "receiverInformation": {
            "companyName": "Purolator",
            "streetAddress": [
                "Pg. de Gràcia, 113"
            ],
            "city": "Barcelona",
            "provinceStateCode": "Catalonia",
            "country": "ES",
            "postalZipCode": "08008"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressInternational",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 20,
            "totalPackages": 2,
            "showAlternativeServicesIndicator": true,
            "packageInformation": [
                {
                    "packageWeight": 10,
                    "packageLength": 4,
                    "packageWidth": 6,
                    "packageHeight": 3
                },
                {
                    "packageWeight": 10,
                    "packageLength": 5,
                    "packageWidth": 5,
                    "packageHeight": 5
                }
            ]
        },
        "billingInformation": {
            "billingAccountNumber": "{{AccountNumber}}",
            "displayPublishedRates": false
        }
    }
}
PURO_BODY,
], __FILE__);
