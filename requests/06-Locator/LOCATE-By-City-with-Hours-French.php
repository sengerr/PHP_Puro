<?php
/**
 * 06 · Locator / LOCATE · By City with Hours (French)
 * GET {{baseUrl}}/locator/v1/address?language=fr&requestReference={{RequestReference}}&city=Vancouver&provinceCode=BC&openTime=08:00:00&closeTime=20:00:00&daysOfOperation=Monday,Tuesday&currentlyOpen=false&wheelChairAccess=false&streetAccess=false&maxNumberOfLocations=2
 *
 * Run:  php requests/06-Locator/LOCATE-By-City-with-Hours-French.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '06 · Locator',
    'name'   => 'LOCATE · By City with Hours (French)',
    'order'  => 958800,
    'method' => 'GET',
    'url'    => '{{baseUrl}}/locator/v1/address?language=fr&requestReference={{RequestReference}}&city=Vancouver&provinceCode=BC&openTime=08:00:00&closeTime=20:00:00&daysOfOperation=Monday,Tuesday&currentlyOpen=false&wheelChairAccess=false&streetAccess=false&maxNumberOfLocations=2',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Variant: city search (OAS example Vancouver) with openTime / closeTime / daysOfOperation / currentlyOpen, language fr.

Source: Purolator-REST-API-STG collection, "Locator - by city with opening hours filters (French)". REST only (no SOAP twin, 2026-10-06).
Header corrected 2026-10-06 after a live Certification check: the client x-api-key works on the locator; the locatorXApiKey / x-origin-verify values from the Locator OAS example are denied (403).
provinceCode=BC added 2026-10-06: Certification answers 9001-31 (city needs a province code) without it.
PURO_TEXT,
], __FILE__);
