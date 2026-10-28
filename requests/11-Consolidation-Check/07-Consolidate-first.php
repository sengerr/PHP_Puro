<?php
/**
 * 11 · Consolidation Check / 07 · Consolidate (first)
 * POST {{baseUrl}}/ship/v1/manifest
 *
 * Run:  php requests/11-Consolidation-Check/07-Consolidate-first.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '07 · Consolidate (first)',
    'order'  => 7000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/manifest',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Consolidation check step 7 of 10.
Converted from the SOAP sample "consolidate.txt". Closes the manifest
  for everything created above. REST has no published consolidate spec;
  /ship/v1/manifest is a behavioural match - see docs/not-converted.md.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "language": "{{language}}",
    "requestReference": "{{RequestReference}}",
    "lineOfBusiness": "COURIER",
    "accountNumber": "{{AccountNumber}}"
}
PURO_BODY,
], __FILE__);
