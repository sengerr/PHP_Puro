<?php
/**
 * 01 · Estimate / ESTIMATE · US · Express
 * POST {{rateBaseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-US-Express.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · US · Express',
    'order'  => 955100,
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
Cross-border estimate CA sender to US receiver (Austin TX). Service PurolatorExpressU.S. Sender remains CA per OAS.

Source: Purolator-REST-API-STG collection, "Estimate - Courier ExpressU.S. CA to US". REST only (no SOAP twin, 2026-10-06).
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
            "streetAddress": ["2310 East Gladwick Street"],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressU.S.",
            "unitOfMeasurement": "Metric",
            "showAlternativeServicesIndicator": true,
            "totalWeight": 3.0,
            "totalPackages": 1,
            "packageInformation": [
                {
                    "packageWeight": 3.0,
                    "packageLength": 25,
                    "packageWidth": 20,
                    "packageHeight": 15
                }
            ]
        }
    }
}
PURO_BODY,
], __FILE__);
