<?php
/**
 * 03 · Returns Management / SAMPLE · Domestic · Validate Return Special Handling Residential Area Heavyweight
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/03-Returns-Management/SAMPLE-Domestic-Validate-Return-Special-Handling-Residential-Area-Heavyweight.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '03 · Returns Management',
    'name'   => 'SAMPLE · Domestic · Validate Return Special Handling Residential Area Heavyweight',
    'order'  => 910400,
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
Team sample “ValidateReturnsManagementShipment- Domestic Shipment -SpecialHandling- Residential Area Heavyweight” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP ValidateReturnsManagementShipmentRequest. Returns Management validate = returnsManagement=true + validateOnly=true.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "",
    "validateOnly": true,
    "returnsManagement": true,
    "rma": "RMA123456",
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "DROPOFF",
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
            "attentionTo": "Home Receiver",
            "streetAddress": [
                "24 Sussex Drive"
            ],
            "city": "Ottawa",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "K1M1P5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGroundReturn",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 10,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 10,
                    "packageLength": 19,
                    "packageWidth": 13,
                    "packageHeight": 2
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "notes": "East"
        },
        "notifications": {
            "requestorEmail": "test.email@purolator.com",
            "subscriptions": [
                {
                    "email": "test.email@purolator.com",
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
            "printerFormat": "Thermal",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
