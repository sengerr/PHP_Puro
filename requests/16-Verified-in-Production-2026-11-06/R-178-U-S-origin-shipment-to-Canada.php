<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-178 · U.S.-origin shipment to Canada
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-178-U-S-origin-shipment-to-Canada.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-178 · U.S.-origin shipment to Canada',
    'order'  => 2300,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-178 (U.S.-origin shipment to Canada).
Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
Placeholders in the body: AccountNumber, Current_Date.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
  "lineOfBusiness": "COURIER",
  "shipmentDate": "{{Current_Date}}",
  "validateOnly": false,
  "returnsManagement": false,
  "returnRates": false,
  "outboundShipment": {
    "usOriginInformation": {
      "companyName": "Sample US Origin",
      "attentionTo": "Shipping",
      "streetAddress": [
        "100 Main St"
      ],
      "city": "Buffalo",
      "provinceStateCode": "NY",
      "country": "US",
      "postalZipCode": "14202",
      "phoneNumber": "18004595599"
    },
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
      "attentionTo": "Tech B",
      "companyName": "Purolator",
      "streetAddress": [
        "3700 Jericho Road"
      ],
      "city": "Richmond",
      "provinceStateCode": "BC",
      "country": "CA",
      "postalZipCode": "V7B1M5",
      "phoneNumber": "18004595599"
    },
    "shipmentInformation": {
      "serviceId": "PurolatorExpress",
      "unitOfMeasurement": "IMPERIAL",
      "descriptionOfGoods": "Consolidation check",
      "totalWeight": 50,
      "totalPackages": 4,
      "dangerousGoodsDeclarationDocumentIndicator": false,
      "packageInformation": [
        {
          "packageWeight": 12.5,
          "packageLength": 10,
          "packageWidth": 10,
          "packageHeight": 10
        },
        {
          "packageWeight": 12.5,
          "packageLength": 10,
          "packageWidth": 10,
          "packageHeight": 10
        },
        {
          "packageWeight": 12.5,
          "packageLength": 10,
          "packageWidth": 10,
          "packageHeight": 10
        },
        {
          "packageWeight": 12.5,
          "packageLength": 10,
          "packageWidth": 10,
          "packageHeight": 10
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
