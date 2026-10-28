<?php
/**
 * 01 · Estimate / SAMPLE · Domestic · Estimate With Return Label
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/SAMPLE-Domestic-Estimate-With-Return-Label.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'SAMPLE · Domestic · Estimate With Return Label',
    'order'  => 900300,
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
Team sample “GetFullEstimate - Domestic Shipment - With Return label” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
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
                "17718 114 Ave"
            ],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4"
        },
        "receiverInformation": {
            "streetAddress": [
                "24 Sussex Drive"
            ],
            "city": "Ottawa",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "K1M1P5"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGround",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 5,
            "totalPackages": 1,
            "showAlternativeServicesIndicator": true,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 5,
                    "packageLength": 12,
                    "packageWidth": 12,
                    "packageHeight": 1
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
