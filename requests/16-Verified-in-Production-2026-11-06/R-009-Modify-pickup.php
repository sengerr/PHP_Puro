<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-009 · Modify pickup
 * PUT {{baseUrl}}/pickup/v1/modify
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-009-Modify-pickup.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-009 · Modify pickup',
    'order'  => 200,
    'method' => 'PUT',
    'url'    => '{{baseUrl}}/pickup/v1/modify',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-009 (Modify pickup).
Expected outcome: HTTP 200 · pickup updated
Placeholders in the body: AccountNumber, PickUpConfirmationNumber.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
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
PURO_BODY,
], __FILE__);
