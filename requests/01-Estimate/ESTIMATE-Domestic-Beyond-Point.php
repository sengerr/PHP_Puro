<?php
/**
 * 01 · Estimate / ESTIMATE · Domestic · Beyond Point
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/01-Estimate/ESTIMATE-Domestic-Beyond-Point.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '01 · Estimate',
    'name'   => 'ESTIMATE · Domestic · Beyond Point',
    'order'  => 22000,
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
Converted from SOAP EstimatingServiceEndpoint.GetFullEstimate — test-aid case “06. Domestic shipment - destination beyond point”.
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
            "streetAddress": [
                "24 Sussex Drive"
            ],
            "city": "Ottawa",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "K1M1P5"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressEnvelope",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 1,
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
                    "packageWeight": 1,
                    "packageLength": 0,
                    "packageWidth": 0,
                    "packageHeight": 0
                }
            ]
        },
        "billingInformation": {
            "billingAccountNumber": "{{AccountNumber}}",
            "displayPublishedRates": false
        }
    },
    "trackingReferenceInformation": {
        "reference1": "REF1",
        "reference2": "REF2",
        "reference3": "REF3"
    }
}
PURO_BODY,
], __FILE__);
