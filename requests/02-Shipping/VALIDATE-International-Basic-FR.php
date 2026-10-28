<?php
/**
 * 02 · Shipping / VALIDATE · International · Basic · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/VALIDATE-International-Basic-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'VALIDATE · International · Basic · FR',
    'order'  => 30002,
    'method' => 'POST',
    'url'    => '{{baseUrl}}{{createShipmentPath}}',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => 'fr',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'dateOffset' => 5,
    'description' => <<<'PURO_TEXT'
French twin of “REST-Validate Shipment-International shipment” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.
Converted from SOAP ShippingServiceEndpoint.ValidateShipment — test-aid case “15. International shipment”.

ValidateShipment maps to the create-shipment endpoint with validateOnly=true.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": true,
    "returnsManagement": false,
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "DROPOFF",
        "senderInformation": {
            "attentionTo": "Tech A",
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
            "country": "DE",
            "postalZipCode": "85501",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressInternational",
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
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "costCenter": "Cost Center",
            "notes": "Special Instructions",
            "shipmentReference1": "REF1",
            "shipmentReference2": "REF2",
            "shipmentReference3": "REF3"
        },
        "crossBorderInformation": {
            "documentsOnlyIndicator": true,
            "nonDocumentsInformation": {
                "currencyForDutiableGoods": "CAD",
                "importExportType": "Permanent",
                "billDutiesToParty": "Sender",
                "businessRelationship": "NotRelated",
                "customsInvoiceDocumentIndicator": true
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
                    "textileIndicator": false
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
