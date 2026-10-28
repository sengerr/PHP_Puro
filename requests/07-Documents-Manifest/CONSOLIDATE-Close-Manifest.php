<?php
/**
 * 07 · Documents & Manifest / CONSOLIDATE · Close Manifest
 * POST {{baseUrl}}/ship/v1/manifest
 *
 * Run:  php requests/07-Documents-Manifest/CONSOLIDATE-Close-Manifest.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'CONSOLIDATE · Close Manifest',
    'order'  => 1600,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/manifest',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Converted from SOAP ShippingServiceEndpoint.Consolidate — test-aid case “19. Consolidate”.

Maps to the REST close-manifest call. The SOAP ConsolidateRequest is empty (it acts on the authenticated account); REST names the account explicitly. The equivalence is a behavioural match confirmed against the UAT sample collection, not a published spec — verify it.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "requestReference": "{{RequestReference}}",
    "lineOfBusiness": "COURIER",
    "accountNumber": "{{AccountNumber}}"
}
PURO_BODY,
], __FILE__);
