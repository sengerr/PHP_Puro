<?php
/**
 * 07 · Documents & Manifest / SAMPLE · Domestic · Manifest Shipment Manifest Document
 * POST {{baseUrl}}/ship/v1/documents/summary-manifest
 *
 * Run:  php requests/07-Documents-Manifest/SAMPLE-Domestic-Manifest-Shipment-Manifest-Document.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'SAMPLE · Domestic · Manifest Shipment Manifest Document',
    'order'  => 904100,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents/summary-manifest',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Team sample “GetShipmentManifestDocument” — sources/reference-collections/Purolator_API_Project_PROD.postman_collection.json.
Naming: SAMPLE · Lane · Focus (tooling/add_team_samples.py). Addresses on the standard fixtures. Not verified live.
Converted from SOAP GetShipmentManifestDocumentRequest. Path and body confirmed from the UAT sample collection.
Corrected 2026-10-06: summary manifest is POST /ship/v1/documents/summary-manifest with manifestDate as an array of dates within today-10 .. today (Purolator REST STG collection, OAS).
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "manifestDate": [
        "{{manifestDate}}"
    ],
    "requestReference": "{{RequestReference}}"
}
PURO_BODY,
], __FILE__);
