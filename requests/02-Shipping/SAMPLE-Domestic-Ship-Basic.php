<?php
/**
 * 02 · Shipping / SAMPLE · Domestic · Ship Basic
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SAMPLE-Domestic-Ship-Basic.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SAMPLE · Domestic · Ship Basic',
    'order'  => 907400,
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
    'capture' => ['vars' => ['PIN', 'trackingId'], 'keys' => ['shipmentpin']],
    'description' => <<<'PURO_TEXT'
Team sample “Create Shipment - Domestic Shipment” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
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
            "attentionTo": "Tech B",
            "companyName": "Purolator",
            "streetAddress": ["30 Aero Drive NE"],
            "city": "Calgary",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T2E8Z9",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGround",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 150,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "DangerousGoods",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "DangerousGoodsClass",
                    "optionIdValue": "FullyRegulated"
                },
                {
                    "optionId": "DangerousGoodsMode",
                    "optionIdValue": "Ground"
                },
                {
                    "optionId": "DeclaredValue",
                    "optionIdValue": "100.00"
                },
                {
                    "optionId": "ChainOfSignature",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "SaturdayPickup",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "SaturdayDelivery",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "ExpressCheque",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "ExpressChequeMethodOfPayment",
                    "optionIdValue": "Cheque"
                },
                {
                    "optionId": "ExpressChequeAmount",
                    "optionIdValue": "100.00"
                },
                {
                    "optionId": "HoldForPickup",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "OriginSignatureNotRequired",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "ResidentialSignatureIntl",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "AdultSignatureRequired",
                    "optionIdValue": "false"
                },
                {
                    "optionId": "HealthCare",
                    "optionIdValue": "false"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 75,
                    "packageLength": 12,
                    "packageWidth": 12,
                    "packageHeight": 1
                },
                {
                    "packageWeight": 75,
                    "packageLength": 12,
                    "packageWidth": 12,
                    "packageHeight": 1
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "Sender",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "notes": "East"
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
