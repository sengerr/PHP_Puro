<?php
/**
 * 02 · Shipping / SHIP · Domestic · Special Handling · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-Special-Handling-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · Special Handling · FR',
    'order'  => 24002,
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
French twin of “REST-Create Shipment-Special Handling” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.

Converted from SOAP ShippingServiceEndpoint.CreateShipment — test-aid case “10. Domestic Shipment - Special Handling”.
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
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 1,
                    "packageLength": 18.01,
                    "packageWidth": 5,
                    "packageHeight": 2
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "notes": "SH05   S15 = AA",
            "shipmentReference1": "TC # 005"
        },
        "notifications": {
            "requestorEmail": "myconf@abc.com",
            "subscriptions": [
                {
                    "email": "myconf@abc.com",
                    "notificationType": {
                        "notificationId": "ShipmentConfirmation-Requester"
                    },
                    "name": "Tech A"
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
