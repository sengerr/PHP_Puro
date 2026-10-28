<?php
/**
 * 11 · Consolidation Check / 08 · Return
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/11-Consolidation-Check/08-Return.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '08 · Return',
    'order'  => 8000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}{{createShipmentPath}}',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Consolidation check step 8 of 10.
Converted from the SOAP sample "create return.txt", Canadian variant.
  The US variant in that sample is rejected: "Outbound Returns/Returns
  Management Shipments are only available for Canadian shipments".
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": true,
    "returnRates": false,
    "rma": "{{rma}}",
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
            "streetAddress": ["62 Vulcan Street"],
            "city": "Etobicoke",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "M9W1L2",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorGroundReturn",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Consolidation check",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 1,
                    "packageLength": 19,
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
            "costCenter": "Consolidation",
            "shipmentReference1": "PO123456"
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
