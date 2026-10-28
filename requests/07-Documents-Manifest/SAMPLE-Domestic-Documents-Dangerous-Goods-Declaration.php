<?php
/**
 * 07 · Documents & Manifest / SAMPLE · Domestic · Documents Dangerous Goods Declaration
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/SAMPLE-Domestic-Documents-Dangerous-Goods-Declaration.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'SAMPLE · Domestic · Documents Dangerous Goods Declaration',
    'order'  => 903800,
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
Team sample “Dangerous Good Declaration” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP GetDocumentsRequest. Path and body confirmed from the UAT sample collection.
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
