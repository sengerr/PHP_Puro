<?php
/**
 * 14 · Document Matrix / DOC · INTL · InternationalBillOfLadingThermal · ZPL · Thermal · BASE64 · FR
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/14-Document-Matrix/DOC-INTL-InternationalBillOfLadingThermal-ZPL-Thermal-BASE64-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '14 · Document Matrix',
    'name'   => 'DOC · INTL · InternationalBillOfLadingThermal · ZPL · Thermal · BASE64 · FR',
    'order'  => 1362,
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
French twin of “DOC · INTL · InternationalBillOfLadingThermal · ZPL · Thermal · BASE64” — same call, pointed at the FRENCH
seed's PIN. The document call does not choose a language: the same PIN
returns the same bytes in en or fr. The seed decides. Language: fr is
sent so any ERROR comes back in French, which is the one thing the
retrieval header does change.
Document matrix: InternationalBillOfLadingThermal from the International seed created as ZPL / Thermal / BASE64.
Reads {{PIN_INTL_ZPL_BASE64_FR}}. documentCriteria carries only shipmentPIN and
documentType -- every format field is rejected as unknown here;
the format came from how the seed was created.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_INTL_ZPL_BASE64_FR}}",
            "documentType": [
                "InternationalBillOfLadingThermal"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
