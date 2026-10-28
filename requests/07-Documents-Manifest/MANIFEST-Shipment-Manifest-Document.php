<?php
/**
 * 07 · Documents & Manifest / MANIFEST · Shipment Manifest Document
 * POST {{baseUrl}}/ship/v1/documents/summary-manifest
 *
 * Run:  php requests/07-Documents-Manifest/MANIFEST-Shipment-Manifest-Document.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'MANIFEST · Shipment Manifest Document',
    'order'  => 1500,
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
Converted from SOAP ShippingDocumentsServiceEndpoint.GetShipmentManifestDocument — test-aid case “08. Shipment Manifest Document”.

Path and body confirmed from the UAT sample collection. REST takes manifestDate as an array.
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
