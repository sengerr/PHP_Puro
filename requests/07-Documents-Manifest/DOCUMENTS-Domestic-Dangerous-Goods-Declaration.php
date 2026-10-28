<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · Domestic · Dangerous Goods Declaration
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-Domestic-Dangerous-Goods-Declaration.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · Domestic · Dangerous Goods Declaration',
    'order'  => 1200,
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
Converted from SOAP ShippingDocumentsServiceEndpoint.GetDocuments — test-aid case “05. Dangerous goods declaration”.

Path and body confirmed from the UAT sample collection. SOAP OutputType/Synchronous have no REST field — thermal vs laser is encoded in the document type name.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_SAMPLE_DG}}",
            "documentType": [
                "DangerousGoodsDeclaration"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
