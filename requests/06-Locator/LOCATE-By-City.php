<?php
/**
 * 06 · Locator / LOCATE · By City
 * GET {{baseUrl}}/locator/v1/address?language={{language}}&requestReference={{RequestReference}}&city={{city}}&provinceCode={{provinceCode}}&radialDistanceInKm=25
 *
 * Run:  php requests/06-Locator/LOCATE-By-City.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '06 · Locator',
    'name'   => 'LOCATE · By City',
    'order'  => 8000,
    'method' => 'GET',
    'url'    => '{{baseUrl}}/locator/v1/address?language={{language}}&requestReference={{RequestReference}}&city={{city}}&provinceCode={{provinceCode}}&radialDistanceInKm=25',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Converted from SOAP LocatorServiceEndpoint.GetLocationsByCity.
Corrected 2026-10-06 from the Purolator REST STG collection and the support cheat sheet: the locator is a GET with language and requestReference as query parameters (it was sent as POST, which the gateway answers 403).
PURO_TEXT,
], __FILE__);
