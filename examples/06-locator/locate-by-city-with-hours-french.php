<?php
/**
 * LOCATE · By City with Hours (French)
 *
 * GET /locator/v1/address   (folder: 06 · Locator)
 *
 * Variant: city search (OAS example Vancouver) with openTime / closeTime / daysOfOperation / currentlyOpen, language fr.
 *
 * Source: Purolator-REST-API-STG collection, "Locator - by city with opening hours filters (French)". REST only (no SOAP twin, 2026-10-06).
 * Header corrected 2026-10-06 after a live Certification check: the client x-api-key works on the locator; the locatorXApiKey / x-origin-verify values from the Locator OAS example are denied (403).
 * provinceCode=BC added 2026-10-06: Certification answers 9001-31 (city needs a province code) without it.
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
 * - `radialDistanceInKm` -- optional, number
 * - `holdForPickup` -- optional, boolean
 * - `dangerousGoods` -- optional, boolean
 * - `kiosk` -- optional, boolean
 * - `streetAccess` -- optional (used here), boolean
 * - `wheelChairAccess` -- optional (used here), boolean
 * - `maxNumberOfLocations` -- optional (used here), integer
 * - `locationType` -- optional, string
 * - `openTime` -- optional (used here), string
 * - `closeTime` -- optional (used here), string
 * - `currentlyOpen` -- optional (used here), boolean
 * - `gmtOffSet` -- optional, string
 * - `daysOfOperation` -- optional (used here), string
 *
 * Run:  php examples/06-locator/locate-by-city-with-hours-french.php [NAME=value ...]
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
    'language' => PurolatorClient::fill('fr', $vars),
    'requestReference' => PurolatorClient::fill('{{RequestReference}}', $vars),
    'city' => PurolatorClient::fill('Vancouver', $vars),
    'provinceCode' => PurolatorClient::fill('BC', $vars),
    'openTime' => PurolatorClient::fill('08:00:00', $vars),
    'closeTime' => PurolatorClient::fill('20:00:00', $vars),
    'daysOfOperation' => PurolatorClient::fill('Monday,Tuesday', $vars),
    'currentlyOpen' => PurolatorClient::fill('false', $vars),
    'wheelChairAccess' => PurolatorClient::fill('false', $vars),
    'streetAccess' => PurolatorClient::fill('false', $vars),
    'maxNumberOfLocations' => PurolatorClient::fill('2', $vars),
];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('GET', '/locator/v1/address', $body, $query, $headers));
