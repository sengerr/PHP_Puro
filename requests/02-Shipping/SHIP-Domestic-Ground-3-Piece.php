<?php
/**
 * 02 · Shipping / SHIP · Domestic · Ground 3-Piece
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Ground-3-Piece.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Ground 3-Piece',
    'order'  => 951000,
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
3-piece Ground; totalPackages=3, totalWeight=12.0.

Source: Purolator-REST-API-STG collection, "Ground Multi-Piece 3 Packages". REST only (no SOAP twin, 2026-10-06).
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
        "pickupType": "PRESCHEDULED",
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
            "streetAddress": ["30 Aero Drive NE"],
            "city": "Calgary",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T2E8Z9",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGround",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Three piece ground",
            "totalWeight": 12.0,
            "totalPackages": 3,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [],
            "packageInformation": [
                {
                    "packageWeight": 4.0,
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
                    "packageWeight": 6.0,
                    "packageLength": 14,
                    "packageWidth": 12,
                    "packageHeight": 10,
                    "packageReference1": "PKG-REF-1",
                    "packageOptionsInformation": [
                        {
                            "optionId": "SpecialHandling",
                            "optionIdValue": "false"
                        }
                    ]
                },
                {
                    "packageWeight": 2.0,
                    "packageLength": 8,
                    "packageWidth": 6,
                    "packageHeight": 4,
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
