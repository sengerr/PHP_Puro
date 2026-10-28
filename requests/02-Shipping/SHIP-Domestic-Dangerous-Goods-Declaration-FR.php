<?php
/**
 * 02 · Shipping / SHIP · Domestic · Dangerous Goods Declaration · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Dangerous-Goods-Declaration-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Dangerous Goods Declaration · FR',
    'order'  => 2002,
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
    'capture' => ['vars' => ['PIN_DangerousGoodsDeclaration_FR'], 'keys' => ['shipmentpin', 'pin']],
    'description' => <<<'PURO_TEXT'
French twin of “REST-Create Shipment-DG request Call and answer” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.

Converted from SOAP CreateShipmentRequest — redactable sample “DG request Call and answer.txt”.
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
                "3700 Jericho Road"
            ],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "METRIC",
            "descriptionOfGoods": "Test Pkg Desc",
            "totalWeight": 5.44,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": true,
            "shipmentOptionsInformation": [
                {
                    "optionId": "DangerousGoods",
                    "optionIdValue": "true"
                },
                {
                    "optionId": "DangerousGoodsClass",
                    "optionIdValue": "UN3373"
                },
                {
                    "optionId": "DangerousGoodsMode",
                    "optionIdValue": "Air"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 5.44,
                    "packageLength": 25.4,
                    "packageWidth": 63.5,
                    "packageHeight": 12.7
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "shipmentReference1": "DG TEST",
            "shipmentReference2": "8081717858",
            "shipmentReference3": "DG RATE-1"
        }
    },
    "shipmentDocuments": {
        "label": {
            "outputType": "PDF",
            "printerFormat": "Laser",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
