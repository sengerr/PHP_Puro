<?php
/**
 * R-008 · Schedule pickup
 *
 * POST /pickup/v1/schedule   (folder: 16 · Verified in Production 2026-11-06)
 *
 * Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-008 (Schedule pickup).
 * Expected outcome: HTTP 200 · pickup scheduled · confirmation number returned
 * Placeholders in the body: AccountNumber, pickupDate.
 * Production calls create real shipments and pickups — void anything you create.
 *
 * Run:  php examples/16-verified-in-production-2026-11-06/r-008-schedule-pickup.php [NAME=value ...]
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
  "lineOfBusiness": "Courier",
  "registeredAccountNumber": "{{AccountNumber}}",
  "pickupInstructions": {
    "date": "{{pickupDate}}",
    "anyTimeAfter": "13:00",
    "untilTime": "17:00",
    "measurementUnit": "Imperial",
    "pickupLocation": "FrontDesk",
    "additionalInstructions": "Sample pickup"
  },
  "pickupAddress": {
    "address": [
      "5995 Avebury Rd"
    ],
    "city": "Mississauga",
    "province": "ON",
    "country": "CA",
    "postalCode": "L5R3T8"
  },
  "shipmentSummary": [
    {
      "destinationCode": "DOM",
      "modeOfTransport": "Ground",
      "totalPieces": 1,
      "totalWeight": 5
    }
  ],
  "contactInfo": {
    "name": "Shipping",
    "company": "Sample Co",
    "phoneNumber": "4161234567"
  }
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/pickup/v1/schedule', $body, $query, $headers));
