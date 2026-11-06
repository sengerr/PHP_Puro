<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-010 · Pickup history
 * POST {{baseUrl}}/pickup/v1/getHistory
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-010-Pickup-history.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-010 · Pickup history',
    'order'  => 300,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/pickup/v1/getHistory',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-010 (Pickup history).
Expected outcome: HTTP 200 · pickup history returned
Placeholders in the body: AccountNumber, PickUpConfirmationNumber.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
  "lineOfBusiness": "Courier",
  "registeredAccountNumber": "{{AccountNumber}}",
  "pickupConfirmationNumber": [
    "{{PickUpConfirmationNumber}}"
  ],
  "status": "Scheduled"
}
PURO_BODY,
], __FILE__);
