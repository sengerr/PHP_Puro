<?php
/**
 * R-009 · Modify pickup
 *
 * PUT /pickup/v1/modify   (folder: 16 · Verified in Production 2026-11-06)
 *
 * Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-009 (Modify pickup).
 * Expected outcome: HTTP 200 · pickup updated
 * Placeholders in the body: AccountNumber, PickUpConfirmationNumber.
 * Production calls create real shipments and pickups — void anything you create.
 *
 * Run:  php examples/16-verified-in-production-2026-11-06/r-009-modify-pickup.php [NAME=value ...]
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
  "pickupConfirmationNumber": "{{PickUpConfirmationNumber}}",
  "pickupInstructions": {
    "untilTime": "18:00",
    "measurementUnit": "imperial",
    "pickupLocation": "BackDoor",
    "additionalInstructions": "Updated sample pickup"
  },
  "shipmentSummary": [
    {
      "destinationCode": "DOM",
      "totalPieces": 2,
      "totalWeight": 14
    }
  ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('PUT', '/pickup/v1/modify', $body, $query, $headers));
