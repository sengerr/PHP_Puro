<?php
/**
 * LOCATE · By Postal Code
 *
 * GET /locator/v1/address   (folder: 06 · Locator)
 *
 * Happy path: required language + requestReference, postalCode M9A0A5, max 2 locations. All other OAS query parameters are listed disabled so trainees can toggle them.
 *
 * Source: Purolator-REST-API-STG collection, "Locator - happy by postal code". REST only (no SOAP twin, 2026-10-06).
 * Optional query parameters in the source (add as needed): streetAddress, city, provinceCode, radialDistanceInKm, holdForPickup, dangerousGoods, kiosk, streetAccess, wheelChairAccess, locationType, openTime, closeTime, currentlyOpen, gmtOffSet, daysOfOperation.
 * Header corrected 2026-10-06 after a live Certification check: the client x-api-key works on the locator; the locatorXApiKey / x-origin-verify values from the Locator OAS example are denied (403).
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
 * - `city` -- optional, string
 * - `postalCode` -- optional (used here), string
 * - `provinceCode` -- optional, string
 * - `radialDistanceInKm` -- optional, number
 * - `holdForPickup` -- optional, boolean
 * - `dangerousGoods` -- optional, boolean
 * - `kiosk` -- optional, boolean
 * - `streetAccess` -- optional, boolean
 * - `wheelChairAccess` -- optional, boolean
 * - `maxNumberOfLocations` -- optional (used here), integer
 * - `locationType` -- optional, string
 * - `openTime` -- optional, string
 * - `closeTime` -- optional, string
 * - `currentlyOpen` -- optional, boolean
 * - `gmtOffSet` -- optional, string
 * - `daysOfOperation` -- optional, string
 *
 * Run:  php examples/06-locator/locate-by-postal-code.php [NAME=value ...]
 * Tier: PUROLATOR_ENV=dev (default) or prod -- see README.md for every variable.
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

// Sample values from the Postman collection; override with NAME=value or PUROLATOR_VAR_<NAME>.
$defaults = [];
$vars = array_merge($defaults, PurolatorClient::argVars($argv));

$body = null;                                     // this call has no body

// Query-string parameters (filled the same way as the body).
$query = [
    'language' => PurolatorClient::fill('en', $vars),
    'requestReference' => PurolatorClient::fill('{{RequestReference}}', $vars),
    'postalCode' => PurolatorClient::fill('M9A0A5', $vars),
    'maxNumberOfLocations' => PurolatorClient::fill('2', $vars),
];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('GET', '/locator/v1/address', $body, $query, $headers));
