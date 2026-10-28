<?php
/**
 * 08 · Product Matrix (Domestic) / PRODUCT · PurolatorExpressBox · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/08-Product-Matrix-Domestic/PRODUCT-PurolatorExpressBox-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '08 · Product Matrix (Domestic)',
    'name'   => 'PRODUCT · PurolatorExpressBox · FR',
    'order'  => 16002,
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
French twin of “PRODUCT · PurolatorExpressBox” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.

Product matrix (API-validated catalogue) - one piece, 2 lb,
destination CA. Identical to every other
request in this folder except serviceId, which is "PurolatorExpressBox".
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
            "streetAddress": ["3700 Jericho Road"],
            "city": "Richmond",
            "provinceStateCode": "BC",
            "country": "CA",
            "postalZipCode": "V7B1M5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressBox",
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
            "shipmentReference1": "PurolatorExpressBox"
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
