<?php
/**
 * 11 · Consolidation Check / 09 · Multi domestic
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/11-Consolidation-Check/09-Multi-domestic.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '09 · Multi domestic',
    'order'  => 9000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}{{createShipmentPath}}',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Consolidation check step 9 of 10.
DERIVED - no sample supplied. Built from "Multipiece.txt" retargeted to
  the domestic receiver, so no customs block is needed.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": false,
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "DROPOFF",
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
            "descriptionOfGoods": "Consolidation check",
            "totalWeight": 50,
            "totalPackages": 4,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "costCenter": "Consolidation",
            "shipmentReference1": "PO123456"
        }
    },
    "shipmentDocuments": {
        "label": {
            "outputType": "ZPL",
            "printerFormat": "thermal",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
