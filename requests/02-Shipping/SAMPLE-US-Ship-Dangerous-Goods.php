<?php
/**
 * 02 · Shipping / SAMPLE · US · Ship Dangerous Goods
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SAMPLE-US-Ship-Dangerous-Goods.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SAMPLE · US · Ship Dangerous Goods',
    'order'  => 909500,
    'method' => 'POST',
    'url'    => '{{baseUrl}}{{createShipmentPath}}',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'dateOffset' => 5,
    'description' => <<<'PURO_TEXT'
Team sample “Create Shipment - International Shipment - Dangerous Goods” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP CreateShipmentRequest.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": false,
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "PRESCHEDULED",
        "senderInformation": {
            "attentionTo": "Tech A",
            "companyName": "Purolator",
            "streetAddress": [
                "17718 114 Ave"
            ],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4",
            "phoneNumber": "17804091270"
        },
        "receiverInformation": {
            "attentionTo": "Retail Agent",
            "streetAddress": [
                "2310 East Gladwick Street"
            ],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 2,
            "totalPackages": 2,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 1,
                    "packageLength": 12,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 1,
                    "packageLength": 12,
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
            "notes": "INSTRUCTIONS",
            "shipmentReference1": "testing",
            "shipmentReference2": "0080012003"
        },
        "crossBorderInformation": {
            "documentsOnlyIndicator": false,
            "nonDocumentsInformation": {
                "currencyForDutiableGoods": "CAD",
                "importExportType": "Permanent",
                "billDutiesToParty": "Sender",
                "businessRelationship": "NotRelated",
                "customsInvoiceDocumentIndicator": true,
                "taxesPaid": "DDP"
            },
            "contentDetails": [
                {
                    "description": "Material1",
                    "harmonizedCode": "3822000002",
                    "countryOfManufacture": "US",
                    "unitValue": 5,
                    "productCode": "71",
                    "quantity": 1,
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": false,
                    "textileIndicator": false,
                    "unitOfMeasure": "Each"
                },
                {
                    "description": "Material1",
                    "harmonizedCode": "3822000002",
                    "countryOfManufacture": "US",
                    "unitValue": 5,
                    "productCode": "71",
                    "quantity": 1,
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": false,
                    "textileIndicator": false,
                    "unitOfMeasure": "Each"
                }
            ]
        }
    },
    "shipmentDocuments": {
        "label": {
            "outputType": "ZPL",
            "printerFormat": "Thermal",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
