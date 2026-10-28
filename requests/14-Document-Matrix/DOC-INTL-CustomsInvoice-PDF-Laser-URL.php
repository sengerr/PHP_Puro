<?php
/**
 * 14 · Document Matrix / DOC · INTL · CustomsInvoice · PDF · Laser · URL
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/14-Document-Matrix/DOC-INTL-CustomsInvoice-PDF-Laser-URL.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '14 · Document Matrix',
    'name'   => 'DOC · INTL · CustomsInvoice · PDF · Laser · URL',
    'order'  => 1510,
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
Document matrix: CustomsInvoice from the International seed created as PDF / Laser / URL.
Reads {{PIN_INTL_PDF_URL}}. documentCriteria carries only shipmentPIN and
documentType -- every format field is rejected as unknown here;
the format came from how the seed was created.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_INTL_PDF_URL}}",
            "documentType": [
                "CustomsInvoice"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
