<?php
/**
 * 02 · Shipping / SHIP · US · Basic
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SHIP-US-Basic.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · US · Basic',
    'order'  => 19000,
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
    'capture' => ['vars' => ['PIN_InternationalBillOfLading', 'PIN_InternationalBillOfLadingThermal', 'PIN2', 'PIN'], 'keys' => ['shipmentpin', 'pin']],
    'description' => <<<'PURO_TEXT'
Converted from SOAP ShippingServiceEndpoint.CreateShipment — test-aid case “09. USA shipment”.
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
            "companyName": "Purolator",
            "streetAddress": ["2310 East Gladwick Street"],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressEnvelopeU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Test Description",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureIntl",
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
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "costCenter": "Cost Center",
            "notes": "Special Instructions",
            "shipmentReference1": "REF1",
            "shipmentReference2": "REF2",
            "shipmentReference3": "REF3"
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
                    "description": "Description",
                    "harmonizedCode": "123",
                    "countryOfManufacture": "CA",
                    "unitValue": 12,
                    "productCode": "123456",
                    "quantity": 34,
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": true,
                    "textileIndicator": false,
                    "unitOfMeasure": "Each"
                }
            ]
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
