<?php
/**
 * R-150 · CODE128
 *
 * POST /ship/v1/shipment   (folder: 16 · Verified in Production 2026-11-06)
 *
 * Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-150 (CODE128).
 * Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
 * Placeholders in the body: AccountNumber, Current_Date.
 * Production calls create real shipments and pickups — void anything you create.
 *
 * Run:  php examples/16-verified-in-production-2026-11-06/r-150-code128.php [NAME=value ...]
 * Tier: PUROLATOR_ENV=dev (default) or prod -- see README.md for every variable.
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

// Sample values from the Postman collection; override with NAME=value or PUROLATOR_VAR_<NAME>.
$defaults = [];
$vars = array_merge($defaults, PurolatorClient::argVars($argv));

// Request body exactly as in the collection; {{placeholders}} are filled below.
$template = <<<'JSON_BODY'
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
      "outputType": "CODE128",
      "printerFormat": "thermal",
      "responseType": "BASE64"
    }
  }
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/shipment', $body, $query, $headers));
