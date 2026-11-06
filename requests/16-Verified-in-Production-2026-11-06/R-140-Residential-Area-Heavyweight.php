<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-140 · Residential Area Heavyweight
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-140-Residential-Area-Heavyweight.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-140 · Residential Area Heavyweight',
    'order'  => 1700,
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
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-140 (Residential Area Heavyweight).
Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
Placeholders in the body: AccountNumber, Current_Date.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
  "lineOfBusiness": "Courier",
  "shipmentDate": "{{Current_Date}}",
  "validateOnly": false,
  "returnsManagement": false,
  "returnRates": false,
  "shipmentDocuments": {
    "label": {
      "outputType": "PDF",
      "printerFormat": "Regular",
      "responseType": "URL"
    }
  },
  "outboundShipment": {
    "pickupType": "DROPOFF",
    "paymentInformation": {
      "paymentType": "SENDER",
      "registeredAccountNumber": "{{AccountNumber}}",
      "billingAccountNumber": "{{AccountNumber}}"
    },
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
        "30 Aero Drive NE"
      ],
      "city": "Calgary",
      "provinceStateCode": "AB",
      "country": "CA",
      "postalZipCode": "T2E8Z9",
      "phoneNumber": "18004595599"
    },
    "shipmentInformation": {
      "serviceId": "PurolatorGround",
      "unitOfMeasurement": "IMPERIAL",
      "descriptionOfGoods": "Test Goods",
      "totalWeight": 10.0,
      "totalPackages": 1,
      "dangerousGoodsDeclarationDocumentIndicator": false,
      "shipmentOptionsInformation": [],
      "packageInformation": [
        {
          "packageWeight": 10.0,
          "packageLength": 20,
          "packageWidth": 16,
          "packageHeight": 12,
          "packageReference1": "PKG-REF-1",
          "packageOptionsInformation": [
            {
              "optionId": "SpecialHandling",
              "optionIdValue": "true"
            },
            {
              "optionId": "SpecialHandlingType",
              "optionIdValue": "ResidentialAreaHeavyweight"
            }
          ]
        }
      ]
    },
    "customerReferenceInformation": {
      "costCenter": "QA-ADDTL",
      "notes": "Additional Postman tests",
      "shipmentReference1": "ADDTL-REF-1"
    }
  }
}
PURO_BODY,
], __FILE__);
