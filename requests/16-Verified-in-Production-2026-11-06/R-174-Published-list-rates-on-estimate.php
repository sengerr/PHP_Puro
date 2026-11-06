<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-174 · Published (list) rates on estimate
 * POST {{baseUrl}}/rate/v1/shipment
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-174-Published-list-rates-on-estimate.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-174 · Published (list) rates on estimate',
    'order'  => 2100,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/rate/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-174 (Published (list) rates on estimate).
Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
Placeholders in the body: AccountNumber, Current_Date.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
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
PURO_BODY,
], __FILE__);
