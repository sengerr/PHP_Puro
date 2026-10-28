<?php
/**
 * 09 · Product Matrix (U.S.) / PRODUCT · PurolatorExpressEnvelopeU.S. · FR
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/09-Product-Matrix-U-S/PRODUCT-PurolatorExpressEnvelopeU-S-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '09 · Product Matrix (U.S.)',
    'name'   => 'PRODUCT · PurolatorExpressEnvelopeU.S. · FR',
    'order'  => 5002,
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
French twin of “PRODUCT · PurolatorExpressEnvelopeU.S.” — identical except the Language header, which is
hard-coded to fr. A document's language is fixed when the SHIPMENT is
created: the same PIN returns the same bytes whether /ship/v1/documents
is called with en or fr, so French coverage needs its own create call.

Product matrix (API-validated catalogue) - one piece, 1 lb,
destination US. Identical to every other
request in this folder except serviceId, which is "PurolatorExpressEnvelopeU.S.".
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
            "serviceId": "PurolatorExpressEnvelopeU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Product matrix test",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 1,
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
            "shipmentReference1": "PurolatorExpressEnvelopeU.S."
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
