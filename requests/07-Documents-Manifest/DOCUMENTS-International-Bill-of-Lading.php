<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · International · Bill of Lading
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-International-Bill-of-Lading.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · International · Bill of Lading',
    'order'  => 3000,
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
REST twin of the SOAP request of the same name (converted with tooling/generators). ShippingDocumentsServiceEndpoint.GetDocuments — Developer Test Aid case “03. International Bill of Lading”. Path and body confirmed from the UAT sample collection.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_US_LASER}}",
            "documentType": [
                "InternationalBillOfLading"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
