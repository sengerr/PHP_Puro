<?php
/**
 * 02 · Shipping / SHIP · Domestic · Ground 9AM Metric
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Ground-9AM-Metric.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Ground 9AM Metric',
    'order'  => 950800,
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
Ground 9AM metric with residential signature and declared value.

Source: Purolator-REST-API-STG collection, "Ground9AM Domestic METRIC". REST only (no SOAP twin, 2026-10-06).
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
            "streetAddress": ["17718 114 Ave"],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4",
            "phoneNumber": "17804091270"
        },
        "receiverInformation": {
            "attentionTo": "Home Receiver",
            "streetAddress": ["24 Sussex Drive"],
            "city": "Ottawa",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "K1M1P5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGround9AM",
            "unitOfMeasurement": "METRIC",
            "descriptionOfGoods": "Test Goods",
            "totalWeight": 4.0,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                },
                {
                    "optionId": "DeclaredValue",
                    "optionIdValue": "100.00"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 4.0,
                    "packageLength": 25,
                    "packageWidth": 20,
                    "packageHeight": 15,
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
