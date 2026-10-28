<?php
/**
 * 14 · Document Matrix / DOC · INTL · InternationalBillOfLading · PDF · Laser · BASE64
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/14-Document-Matrix/DOC-INTL-InternationalBillOfLading-PDF-Laser-BASE64.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '14 · Document Matrix',
    'name'   => 'DOC · INTL · InternationalBillOfLading · PDF · Laser · BASE64',
    'order'  => 1480,
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
Document matrix: InternationalBillOfLading from the International seed created as PDF / Laser / BASE64.
Reads {{PIN_INTL_PDF_BASE64}}. documentCriteria carries only shipmentPIN and
documentType -- every format field is rejected as unknown here;
the format came from how the seed was created.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_INTL_PDF_BASE64}}",
            "documentType": [
                "InternationalBillOfLading"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
