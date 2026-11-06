<?php
/**
 * R-174 · Published (list) rates on estimate
 *
 * POST /rate/v1/shipment   (folder: 16 · Verified in Production 2026-11-06)
 *
 * Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-174 (Published (list) rates on estimate).
 * Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
 * Placeholders in the body: AccountNumber, Current_Date.
 * Production calls create real shipments and pickups — void anything you create.
 *
 * Run:  php examples/16-verified-in-production-2026-11-06/r-174-published-list-rates-on-estimate.php [NAME=value ...]
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
  "outboundShipment": {
    "senderInformation": {
      "companyName": "Purolator",
      "streetAddress": [
        "17718 114 Ave"
      ],
      "city": "Edmonton",
      "provinceStateCode": "AB",
      "country": "CA",
      "postalZipCode": "T5S2N4"
    },
    "receiverInformation": {
      "companyName": "Purolator",
      "streetAddress": [
        "30 Aero Drive NE"
      ],
      "city": "Calgary",
      "provinceStateCode": "AB",
      "country": "CA",
      "postalZipCode": "T2E8Z9"
    },
    "shipmentInformation": {
      "serviceId": "PurolatorGround",
      "unitOfMeasurement": "IMPERIAL",
      "totalWeight": 150,
      "totalPackages": 1,
      "showAlternativeServicesIndicator": false,
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
    "billingInformation": {
      "displayPublishedRates": false,
      "billingAccountNumber": "{{AccountNumber}}"
    }
  }
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/rate/v1/shipment', $body, $query, $headers));
