<?php
/**
 * 14 · Document Matrix / DOC · DOM · DomesticBillOfLadingThermal · ZPL · Thermal · STRING · FR
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/14-Document-Matrix/DOC-DOM-DomesticBillOfLadingThermal-ZPL-Thermal-STRING-FR.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '14 · Document Matrix',
    'name'   => 'DOC · DOM · DomesticBillOfLadingThermal · ZPL · Thermal · STRING · FR',
    'order'  => 1022,
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
French twin of “DOC · DOM · DomesticBillOfLadingThermal · ZPL · Thermal · STRING” — same call, pointed at the FRENCH
seed's PIN. The document call does not choose a language: the same PIN
returns the same bytes in en or fr. The seed decides. Language: fr is
sent so any ERROR comes back in French, which is the one thing the
retrieval header does change.
Document matrix: DomesticBillOfLadingThermal from the Domestic seed created as ZPL / Thermal / STRING.
Reads {{PIN_DOM_ZPL_STRING_FR}}. documentCriteria carries only shipmentPIN and
documentType -- every format field is rejected as unknown here;
the format came from how the seed was created.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "COURIER",
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_DOM_ZPL_STRING_FR}}",
            "documentType": [
                "DomesticBillOfLadingThermal"
            ]
        }
    ]
}
PURO_BODY,
], __FILE__);
