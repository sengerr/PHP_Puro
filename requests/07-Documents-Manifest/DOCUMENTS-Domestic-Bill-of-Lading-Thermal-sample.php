<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · Domestic · Bill of Lading (Thermal, sample)
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-Domestic-Bill-of-Lading-Thermal-sample.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · Domestic · Bill of Lading (Thermal, sample)',
    'order'  => 700,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Converted from SOAP GetDocumentsRequest — redactable sample “ZPL thermal doc call and answer.txt”.

Path and body confirmed from the UAT sample collection.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN}}",
            "documentType": [
                "DomesticBillOfLadingThermal"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
