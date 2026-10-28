<?php
/**
 * 02 · Shipping / SHIP · Domestic · 2-Piece Multi-Option
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SHIP-Domestic-2-Piece-Multi-Option.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SHIP · Domestic · 2-Piece Multi-Option',
    'order'  => 3000,
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
Converted from SOAP CreateShipmentRequest — redactable sample “Full run - multi/2 piece create.txt”.
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
            "totalPackages": 2,
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
            "printerFormat": "thermal",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
