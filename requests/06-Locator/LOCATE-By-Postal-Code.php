<?php
/**
 * 06 · Locator / LOCATE · By Postal Code
 * GET {{baseUrl}}/locator/v1/address?language=en&requestReference={{RequestReference}}&postalCode=M9A0A5&maxNumberOfLocations=2
 *
 * Run:  php requests/06-Locator/LOCATE-By-Postal-Code.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '06 · Locator',
    'name'   => 'LOCATE · By Postal Code',
    'order'  => 958600,
    'method' => 'GET',
    'url'    => '{{baseUrl}}/locator/v1/address?language=en&requestReference={{RequestReference}}&postalCode=M9A0A5&maxNumberOfLocations=2',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Happy path: required language + requestReference, postalCode M9A0A5, max 2 locations. All other OAS query parameters are listed disabled so trainees can toggle them.

Source: Purolator-REST-API-STG collection, "Locator - happy by postal code". REST only (no SOAP twin, 2026-10-06).
Optional query parameters in the source (add as needed): streetAddress, city, provinceCode, radialDistanceInKm, holdForPickup, dangerousGoods, kiosk, streetAccess, wheelChairAccess, locationType, openTime, closeTime, currentlyOpen, gmtOffSet, daysOfOperation.
Header corrected 2026-10-06 after a live Certification check: the client x-api-key works on the locator; the locatorXApiKey / x-origin-verify values from the Locator OAS example are denied (403).
PURO_TEXT,
], __FILE__);
