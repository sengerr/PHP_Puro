<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · Batch 2 PINs
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-Batch-2-PINs.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · Batch 2 PINs',
    'order'  => 952700,
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
OAS: documentCriteria 1–50. Demo multi-PIN support workflow when Create omitted docs for size.

Source: Purolator-REST-API-STG collection, "POST Documents - batch 2 PINs". REST only (no SOAP twin, 2026-10-06).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN}}"
        },
        {
            "shipmentPIN": "{{PIN2}}"
        }
    ]
}
PURO_BODY,
], __FILE__);
