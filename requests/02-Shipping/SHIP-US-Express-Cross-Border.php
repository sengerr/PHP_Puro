<?php
/**
 * 02 · Shipping / SHIP · US · Express Cross-Border
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/02-Shipping/SHIP-US-Express-Cross-Border.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · US · Express Cross-Border',
    'order'  => 951700,
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
GOLDEN BODY (user-confirmed working): CA -> US Express U.S. to Austin, TX with non-documents crossBorderInformation. Key fixes vs the older body: contentDetails[].productCode = "ELEC-001", contentDetails[].unitOfMeasure = "box" (not "EA"), nonDocumentsInformation.businessRelationship = "related" (not "FALSE"). Use this body as the template for every US / cross-border Create.

Source: Purolator-REST-API-STG collection, "ExpressU.S. CA to US with CrossBorder". REST only (no SOAP twin, 2026-10-06).
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
            "serviceId": "PurolatorExpressU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Cross-border goods",
            "totalWeight": 5.0,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureIntl",
                    "optionIdValue": "false"
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
                }
            ]
        },
        "customerReferenceInformation": {
            "costCenter": "QA-ADDTL",
            "notes": "Additional Postman tests",
            "shipmentReference1": "ADDTL-REF-1"
        },
        "crossBorderInformation": {
            "documentsOnlyIndicator": false,
            "nonDocumentsInformation": {
                "taxesPaid": "DDU",
                "currencyForDutiableGoods": "CAD",
                "importExportType": "Permanent",
                "billDutiesToParty": "Sender",
                "businessRelationship": "related",
                "customsInvoiceDocumentIndicator": true
            },
            "contentDetails": [
                {
                    "description": "Electronics",
                    "productCode": "ELEC-001",
                    "harmonizedCode": "8471.30",
                    "countryOfManufacture": "CA",
                    "unitValue": 150.0,
                    "quantity": 1,
                    "unitOfMeasure": "box",
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": false,
                    "textileIndicator": false
                }
            ]
        }
    }
}
PURO_BODY,
], __FILE__);
