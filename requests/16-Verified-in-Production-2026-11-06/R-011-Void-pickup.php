<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-011 · Void pickup
 * PUT {{baseUrl}}/pickup/v1/void
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-011-Void-pickup.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-011 · Void pickup',
    'order'  => 400,
    'method' => 'PUT',
    'url'    => '{{baseUrl}}/pickup/v1/void',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-011 (Void pickup).
Expected outcome: HTTP 200 · pickup cancelled
Placeholders in the body: PickUpConfirmationNumber.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
  "lineOfBusiness": "Courier",
  "pickupConfirmationNumber": "{{PickUpConfirmationNumber}}"
}
PURO_BODY,
], __FILE__);
