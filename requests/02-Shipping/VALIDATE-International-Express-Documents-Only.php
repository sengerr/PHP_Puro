<?php
/**
 * 02 · Shipping / VALIDATE · International · Express Documents Only
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/VALIDATE-International-Express-Documents-Only.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'VALIDATE · International · Express Documents Only',
    'order'  => 951900,
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
    'description' => <<<'PURO_TEXT'
International Express documents-only to HK (validateOnly).

Source: Purolator-REST-API-STG collection, "ExpressINTL CA to HK Documents Validate Only". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": true,
    "returnsManagement": false,
    "returnRates": true,
    "shipmentDocuments": {
        "label": {
            "outputType": "PDF",
            "printerFormat": "Regular",
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
            "streetAddress": ["3700 Jericho Road"],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5",
            "phoneNumber": "16042734333"
        },
        "receiverInformation": {
            "attentionTo": "Tech B",
            "companyName": "Purolator",
            "streetAddress": ["Lang Strasse 59"],
            "city": "Ottobrunn",
            "provinceStateCode": "",
            "country": "DE",
            "postalZipCode": "85501",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressInternational",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Documents",
            "totalWeight": 1.0,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [],
            "packageInformation": [
                {
                    "packageWeight": 1.0,
                    "packageLength": 10,
                    "packageWidth": 8,
                    "packageHeight": 2,
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
        },
        "crossBorderInformation": {
            "documentsOnlyIndicator": true
        }
    }
}
PURO_BODY,
], __FILE__);
