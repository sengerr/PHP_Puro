<?php
/**
 * 02 · Shipping / VALIDATE · Domestic · Special Handling
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/VALIDATE-Domestic-Special-Handling.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'VALIDATE · Domestic · Special Handling',
    'order'  => 34000,
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
Converted from SOAP ShippingServiceEndpoint.ValidateShipment — test-aid case “17. Domestic Shipment with Special Handling”.

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
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Purolator Express",
            "totalWeight": 80,
            "totalPackages": 4,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 40,
                    "packageLength": 40,
                    "packageWidth": 10,
                    "packageHeight": 2
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "notes": "No Instructions",
            "shipmentReference1": "IPTEWSFEstProdAct20086111",
            "shipmentReference2": "IPTEWSFEstProdAct20086111",
            "shipmentReference3": "IPTEWSFEstProdAct20086111",
            "shipmentReference4": "IPTEWSFEstProdAct20086111"
        },
        "notifications": {
            "requestorEmail": "mahnoor.qureshi@innovapost.com",
            "subscriptions": [
                {
                    "email": "mahnoor.qureshi@innovapost.com",
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
