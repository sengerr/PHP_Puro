<?php
/**
 * 02 · Shipping / SHIP · Domestic · Express 2-Piece
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Express-2-Piece.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Express 2-Piece',
    'order'  => 950900,
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
2-piece Express; totalPackages=2, totalWeight=sum(5+3.5)=8.5.

Source: Purolator-REST-API-STG collection, "Express Multi-Piece 2 Packages". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
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
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Multi-piece electronics",
            "totalWeight": 8.5,
            "totalPackages": 2,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 5.0,
                    "packageLength": 12,
                    "packageWidth": 10,
                    "packageHeight": 8,
                    "packageReference1": "PKG-REF-1",
                    "packageOptionsInformation": [
                        {
                            "optionId": "SpecialHandling",
                            "optionIdValue": "false"
                        }
                    ]
                },
                {
                    "packageWeight": 3.5,
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
