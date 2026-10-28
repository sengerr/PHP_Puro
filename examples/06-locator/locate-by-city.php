<?php
/**
 * LOCATE · By City
 *
 * GET /locator/v1/address   (folder: 06 · Locator)
 *
 * Converted from SOAP LocatorServiceEndpoint.GetLocationsByCity.
 * Corrected 2026-10-06 from the Purolator REST STG collection and the support cheat sheet: the locator is a GET with language and requestReference as query parameters (it was sent as POST, which the gateway answers 403).
 *
 * ### Developer notes
 * `GET /locator/v1/address` -- Locator spec. Retrieve locator address information
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `x-origin-verify` (required) · `access-control-allow-origin` (required)
 * The spec marks `x-origin-verify` and `access-control-allow-origin` as required; Certification answered 200 without them (2026-10-06). Send `{{xOriginVerify}}` only if Purolator issued you one.
 *
 * **Query parameters:**
 * - `language` -- required (used here), string
 * - `requestReference` -- required (used here), string
 * - `streetAddress` -- optional, string
 * - `city` -- optional (used here), string
 * - `postalCode` -- optional, string
 * - `provinceCode` -- optional (used here), string
 * - `radialDistanceInKm` -- optional (used here), number
 * - `holdForPickup` -- optional, boolean
 * - `dangerousGoods` -- optional, boolean
 * - `kiosk` -- optional, boolean
 * - `streetAccess` -- optional, boolean
 * - `wheelChairAccess` -- optional, boolean
 * - `maxNumberOfLocations` -- optional, integer
 * - `locationType` -- optional, string
 * - `openTime` -- optional, string
 * - `closeTime` -- optional, string
 * - `currentlyOpen` -- optional, boolean
 * - `gmtOffSet` -- optional, string
 * - `daysOfOperation` -- optional, string
 *
 * Run:  php examples/06-locator/locate-by-city.php [NAME=value ...]
 * Tier: PUROLATOR_ENV=dev (default) or prod -- see README.md for every variable.
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

// Sample values from the Postman collection; override with NAME=value or PUROLATOR_VAR_<NAME>.
$defaults = ['city' => 'Toronto', 'provinceCode' => 'ON'];
$vars = array_merge($defaults, PurolatorClient::argVars($argv));

$body = null;                                     // this call has no body

// Query-string parameters (filled the same way as the body).
$query = [
    'language' => PurolatorClient::fill('{{language}}', $vars),
    'requestReference' => PurolatorClient::fill('{{RequestReference}}', $vars),
    'city' => PurolatorClient::fill('{{city}}', $vars),
    'provinceCode' => PurolatorClient::fill('{{provinceCode}}', $vars),
    'radialDistanceInKm' => PurolatorClient::fill('25', $vars),
];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('GET', '/locator/v1/address', $body, $query, $headers));
