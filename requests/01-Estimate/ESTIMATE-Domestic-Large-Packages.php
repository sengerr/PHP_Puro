<?php
/**
 * 01 · Estimate / ESTIMATE · Domestic · Large Packages
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-Domestic-Large-Packages.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · Domestic · Large Packages',
    'order'  => 5000,
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
Converted from SOAP GetFullEstimateRequest — redactable sample “Full estimate  - large packages.txt”.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "outboundShipment": {
        "senderInformation": {
            "streetAddress": [
                "17718 114 Ave"
            ],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4"
        },
        "receiverInformation": {
            "companyName": "Purolator",
            "streetAddress": [
                "3700 Jericho Road"
            ],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 176,
            "totalPackages": 2,
            "showAlternativeServicesIndicator": true,
            "packageInformation": [
                {
                    "packageWeight": 100,
                    "packageLength": 24,
                    "packageWidth": 24,
                    "packageHeight": 24
                },
                {
                    "packageWeight": 76,
                    "packageLength": 24,
                    "packageWidth": 24,
                    "packageHeight": 25
                }
            ]
        },
        "billingInformation": {
            "billingAccountNumber": "{{AccountNumber}}",
            "displayPublishedRates": false
        }
    },
    "trackingReferenceInformation": {
        "reference1": "Oversize",
        "reference2": "Large Package",
        "reference3": "Multipiece"
    }
}
PURO_BODY,
], __FILE__);
