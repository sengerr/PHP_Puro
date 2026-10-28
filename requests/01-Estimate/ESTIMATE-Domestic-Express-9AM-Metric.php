<?php
/**
 * 01 · Estimate / ESTIMATE · Domestic · Express 9AM Metric
 * POST {{rateBaseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-Domestic-Express-9AM-Metric.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · Domestic · Express 9AM Metric',
    'order'  => 954800,
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
Happy path. Courier Express 9AM, Mississauga to Mississauga, Metric. Expect outboundShipmentEstimates.

Source: Purolator-REST-API-STG collection, "Estimate - Courier Express9AM domestic Metric (happy)". REST only (no SOAP twin, 2026-10-06).
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
            "streetAddress": ["30 Aero Drive NE"],
            "city": "Calgary",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T2E8Z9"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress9AM",
            "unitOfMeasurement": "Metric",
            "showAlternativeServicesIndicator": true,
            "totalWeight": 2.5,
            "totalPackages": 1,
            "packageInformation": [
                {
                    "packageWeight": 2.5,
                    "packageLength": 30,
                    "packageWidth": 20,
                    "packageHeight": 15
                }
            ]
        }
    }
}
PURO_BODY,
], __FILE__);
