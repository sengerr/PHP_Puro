<?php
/**
 * 09 · Product Matrix (US-Intl) / PRODUCT · PurolatorExpressBoxInternational
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/09-Product-Matrix-US-Intl/PRODUCT-PurolatorExpressBoxInternational.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '09 · Product Matrix (US-Intl)',
    'name'   => 'PRODUCT · PurolatorExpressBoxInternational',
    'order'  => 30000,
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
Product matrix (full requested list, verbatim) - one piece, 2 lb, destination IL. Identical to every other request in this folder except serviceId, which is "PurolatorExpressBoxInternational".
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
            "serviceId": "PurolatorExpressBoxInternational",
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
            "shipmentReference1": "PurolatorExpressBoxInternational"
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
