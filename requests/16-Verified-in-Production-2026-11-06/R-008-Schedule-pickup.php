<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-008 · Schedule pickup
 * POST {{baseUrl}}/pickup/v1/schedule
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-008-Schedule-pickup.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-008 · Schedule pickup',
    'order'  => 100,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/pickup/v1/schedule',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'capture' => ['vars' => ['PickUpConfirmationNumber'], 'keys' => ['pickupconfirmationnumber']],
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-008 (Schedule pickup).
Expected outcome: HTTP 200 · pickup scheduled · confirmation number returned
Placeholders in the body: AccountNumber, pickupDate.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
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
PURO_BODY,
], __FILE__);
