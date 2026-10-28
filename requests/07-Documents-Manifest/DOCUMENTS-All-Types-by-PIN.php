<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · All Types by PIN
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-All-Types-by-PIN.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · All Types by PIN',
    'order'  => 952500,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
OAS + cheat sheet happy path. Omit documentType → all eligible docs. Requires {{shipmentPIN}} from a prior create. Saves nothing. Expected 200.

Source: Purolator-REST-API-STG collection, "POST Documents - happy by shipmentPIN (all types)". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN}}"
        }
    ]
}
PURO_BODY,
], __FILE__);
