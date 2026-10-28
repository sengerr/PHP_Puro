<?php
/**
 * 02 · Shipping / SHIP · Domestic · Label ZPL Thermal URL
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Label-ZPL-Thermal-URL.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Label ZPL Thermal URL',
    'order'  => 951100,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'dateOffset' => 5,
    'capture' => ['vars' => ['PIN'], 'keys' => ['shipmentpin']],
    'description' => <<<'PURO_TEXT'
ZPL thermal label returned as URL.

Source: Purolator-REST-API-STG collection, "Label ZPL Thermal URL". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": false,
    "returnRates": false,
    "shipmentDocuments": {
        "label": {
            "outputType": "ZPL",
            "printerFormat": "Thermal",
            "responseType": "URL"
        }
    },
    "outboundShipment": {
        "pickupType": "DROPOFF",
        "paymentInformation": {
            "paymentType": "SENDER",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "senderInformation": {
            "attentionTo": "Tech A",
            "companyName": "Purolator",
            "streetAddress": ["17718 114 Ave"],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4",
            "phoneNumber": "17804091270"
        },
        "receiverInformation": {
            "attentionTo": "Tech B",
            "companyName": "Purolator",
            "streetAddress": ["3700 Jericho Road"],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Test Goods",
            "totalWeight": 2.0,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [],
            "packageInformation": [
                {
                    "packageWeight": 2.0,
                    "packageLength": 10,
                    "packageWidth": 8,
                    "packageHeight": 6,
                    "packageReference1": "PKG-REF-1",
                    "packageOptionsInformation": [
                        {
                            "optionId": "SpecialHandling",
                            "optionIdValue": "false"
                        }
                    ]
                }
            ]
        },
        "customerReferenceInformation": {
            "costCenter": "QA-ADDTL",
            "notes": "Additional Postman tests",
            "shipmentReference1": "ADDTL-REF-1"
        }
    }
}
PURO_BODY,
], __FILE__);
