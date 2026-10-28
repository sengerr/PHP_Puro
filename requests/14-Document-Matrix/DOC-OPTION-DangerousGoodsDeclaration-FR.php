<?php
/**
 * 14 · Document Matrix / DOC · OPTION · DangerousGoodsDeclaration · FR
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/14-Document-Matrix/DOC-OPTION-DangerousGoodsDeclaration-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '14 · Document Matrix',
    'name'   => 'DOC · OPTION · DangerousGoodsDeclaration · FR',
    'order'  => 1542,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => 'fr',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
French twin of “DOC · OPTION · DangerousGoodsDeclaration” — same call, pointed at the FRENCH
seed's PIN. The document call does not choose a language: the same PIN
returns the same bytes in en or fr. The seed decides. Language: fr is
sent so any ERROR comes back in French, which is the one thing the
retrieval header does change.
Document matrix: DangerousGoodsDeclaration, which depends on a shipment OPTION rather than
the label format. Reads {{PIN_DangerousGoodsDeclaration_FR}}, captured by the Dangerous Goods seed in 02 · Shipping, so
there is one request rather than one per format combination.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_DangerousGoodsDeclaration_FR}}",
            "documentType": [
                "DangerousGoodsDeclaration"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
