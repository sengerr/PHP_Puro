<?php
/**
 * 02 · Shipping / SAMPLE · Domestic · Validate Dangerous Good
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/02-Shipping/SAMPLE-Domestic-Validate-Dangerous-Good.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '02 · Shipping',
    'name'   => 'SAMPLE · Domestic · Validate Dangerous Good',
    'order'  => 907100,
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
Team sample “Validate Shipment - Domestic Shipment - Dangerous Good” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP ValidateShipmentRequest. ValidateShipment maps to the create-shipment endpoint with validateOnly=true.
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
            "totalWeight": 10,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": true,
            "shipmentOptionsInformation": [
                {
                    "optionId": "DangerousGoods",
                    "optionIdValue": "true"
                },
                {
                    "optionId": "DangerousGoodsClass",
                    "optionIdValue": "FullyRegulated"
                },
                {
                    "optionId": "DangerousGoodsMode",
                    "optionIdValue": "Ground"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 10,
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
            "outputType": "PDF",
            "printerFormat": "Laser",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
