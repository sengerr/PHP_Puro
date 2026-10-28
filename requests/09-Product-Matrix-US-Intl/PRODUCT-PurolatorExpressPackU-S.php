<?php
/**
 * 09 · Product Matrix (US-Intl) / PRODUCT · PurolatorExpressPackU.S.
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/09-Product-Matrix-US-Intl/PRODUCT-PurolatorExpressPackU-S.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '09 · Product Matrix (US-Intl)',
    'name'   => 'PRODUCT · PurolatorExpressPackU.S.',
    'order'  => 9000,
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
Product matrix (full requested list, verbatim) - one piece, 2 lb, destination US. Identical to every other request in this folder except serviceId, which is "PurolatorExpressPackU.S.".
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
            "attentionTo": "Retail Agent",
            "companyName": "Purolator",
            "streetAddress": [
                "2310 East Gladwick Street"
            ],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressPackU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Product matrix test",
            "totalWeight": 2,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 2,
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
            "costCenter": "Product Matrix",
            "shipmentReference1": "PurolatorExpressPackU.S."
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
