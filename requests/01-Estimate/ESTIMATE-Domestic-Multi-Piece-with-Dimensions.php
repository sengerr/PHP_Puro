<?php
/**
 * 01 · Estimate / ESTIMATE · Domestic · Multi-Piece with Dimensions
 * POST {{rateBaseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-Domestic-Multi-Piece-with-Dimensions.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · Domestic · Multi-Piece with Dimensions',
    'order'  => 955000,
    'method' => 'POST',
    'url'    => '{{rateBaseUrl}}/rate/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'dateOffset' => 5,
    'description' => <<<'PURO_TEXT'
Multi-piece Courier estimate. totalPackages=2 with packageInformation weights and dimensions. Piece option SpecialHandling.

Source: Purolator-REST-API-STG collection, "Estimate - Courier multi-piece with dimensions". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentDate": "{{Current_Date}}",
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
            "streetAddress": ["3700 Jericho Road"],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "Metric",
            "showAlternativeServicesIndicator": true,
            "totalWeight": 8.5,
            "totalPackages": 2,
            "packageInformation": [
                {
                    "packageWeight": 5.0,
                    "packageLength": 30.0,
                    "packageWidth": 25.0,
                    "packageHeight": 20.0,
                    "packageOptionsInformation": [
                        {
                            "optionId": "SpecialHandling",
                            "optionIdValue": "false"
                        }
                    ]
                },
                {
                    "packageWeight": 3.5,
                    "packageLength": 25.0,
                    "packageWidth": 20.0,
                    "packageHeight": 15.0
                }
            ]
        }
    }
}
PURO_BODY,
], __FILE__);
