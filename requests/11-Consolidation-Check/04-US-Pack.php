<?php
/**
 * 11 · Consolidation Check / 04 · US Pack
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/11-Consolidation-Check/04-US-Pack.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '04 · US Pack',
    'order'  => 4000,
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
Consolidation check step 4 of 10.
DERIVED - no sample supplied. Built from "express box.txt" with the
  Pack service substituted.
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
            "attentionTo": "Retail Agent",
            "streetAddress": ["2310 East Gladwick Street"],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressPackU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Consolidation check",
            "totalWeight": 5,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 5,
                    "packageLength": 11,
                    "packageWidth": 8,
                    "packageHeight": 1
                }
            ]
        },
        "crossBorderInformation": {
            "nonDocumentsInformation": {
                "importExportType": "Permanent",
                "customsInvoiceDocumentIndicator": true
            },
            "contentDetails": [
                {
                    "description": "Wireless Router",
                    "harmonizedCode": "8517620090",
                    "countryOfManufacture": "CN",
                    "unitValue": 120,
                    "productCode": "WR-AX6000",
                    "quantity": 1,
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": true,
                    "textileIndicator": false
                }
            ]
        },        "paymentInformation": {
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
