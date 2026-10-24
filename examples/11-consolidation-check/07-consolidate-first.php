<?php
/**
 * 07 · Consolidate (first)
 *
 * POST /ship/v1/manifest   (folder: 11 · Consolidation Check)
 *
 * Consolidation check step 7 of 10.
 * Converted from the SOAP sample "consolidate.txt". Closes the manifest
 *   for everything created above. REST has no published consolidate spec;
 *   /ship/v1/manifest is a behavioural match - see docs/not-converted.md.
 *
 * ### Developer notes
 * `POST /ship/v1/manifest` -- Close Manifest spec (CloseManifestRequest). Close manifest
 *
 * Trigger end-of-day close (consolidate) of the courier manifest for an account. Freight is not supported.
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36
 *
 * **Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `accountNumber`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `language` -- string, default EN; accepted: `EN` | `FR`. Response language. Case-insensitive. If not provided, defaults to EN (English).
 * - `requestReference` -- string, max 36 chars. A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
 * - `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
 * - `accountNumber`* -- string, length 1-15
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/11-consolidation-check/07-consolidate-first.php [NAME=value ...]
 * Tier: PUROLATOR_ENV=dev (default) or prod -- see README.md for every variable.
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

// Sample values from the Postman collection; override with NAME=value or PUROLATOR_VAR_<NAME>.
$defaults = [];
$vars = array_merge($defaults, PurolatorClient::argVars($argv));

// Request body exactly as in the collection; {{placeholders}} are filled below.
$template = <<<'JSON_BODY'
{
    "language": "{{language}}",
    "requestReference": "{{RequestReference}}",
    "lineOfBusiness": "Courier",
    "accountNumber": "{{AccountNumber}}"
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/manifest', $body, $query, $headers));
