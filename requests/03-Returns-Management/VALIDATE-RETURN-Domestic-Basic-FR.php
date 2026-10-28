<?php
/**
 * 03 · Returns Management / VALIDATE RETURN · Domestic · Basic · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/03-Returns-Management/VALIDATE-RETURN-Domestic-Basic-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '03 · Returns Management',
    'name'   => 'VALIDATE RETURN · Domestic · Basic · FR',
    'order'  => 5002,
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
French twin of “REST-Validate Returns Shipment-Domestic shipment” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.
Converted from SOAP ReturnsManagementServiceEndpoint.ValidateReturnsManagementShipment — test-aid case “03. Domestic shipment”.

Returns Management validate = returnsManagement=true + validateOnly=true.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": true,
    "returnsManagement": true,
    "rma": "{{rma}}",
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
            "attentionTo": "Tech B",
            "companyName": "Purolator",
            "streetAddress": [
                "62 Vulcan Street"
            ],
            "city": "Etobicoke",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "M9W1L2",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressReturn",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "My Description",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 1,
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
            "costCenter": "Cost Center",
            "notes": "Special Instructions",
            "shipmentReference1": "REF1",
            "shipmentReference2": "REF2",
            "shipmentReference3": "REF3"
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
