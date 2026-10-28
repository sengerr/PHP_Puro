<?php
/**
 * 01 · Estimate / ESTIMATE · International · Costa Rica
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-International-Costa-Rica.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · International · Costa Rica',
    'order'  => 9000,
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
Converted from SOAP GetFullEstimateRequest — redactable sample “international estimate/Costa Rico Estimate.txt”.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "outboundShipment": {
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
            "companyName": "Purolator",
            "streetAddress": [
                "Central"
            ],
            "city": "Mata Redonda",
            "provinceStateCode": "San Jose",
            "country": "CR",
            "postalZipCode": "10105"
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
    },
    "trackingReferenceInformation": {
        "reference1": "Oversize",
        "reference2": "Large Package",
        "reference3": "Multipiece"
    }
}
PURO_BODY,
], __FILE__);
