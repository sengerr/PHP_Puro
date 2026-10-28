<?php
/**
 * 07 · Documents & Manifest / DOCUMENTS · Batch 2 PINs
 * POST {{baseUrl}}/ship/v1/documents
 *
 * Run:  php requests/07-Documents-Manifest/DOCUMENTS-Batch-2-PINs.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '07 · Documents & Manifest',
    'name'   => 'DOCUMENTS · Batch 2 PINs',
    'order'  => 952700,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/documents',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'Language' => '{{language}}',
        'RequestReference' => '{{RequestReference}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
OAS: documentCriteria 1–50. Demo multi-PIN support workflow when Create omitted docs for size.

Source: Purolator-REST-API-STG collection, "POST Documents - batch 2 PINs". REST only (no SOAP twin, 2026-10-06).

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /ship/v1/documents` -- Get Documents spec (GetDocumentsRequest). Get shipment documents

Retrieve documents for one or more shipment PINs. Freight is not supported.

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36

**Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `documentCriteria`, `documentCriteria[].shipmentPIN`

**Fields in this body** (type, limits, accepted values; * = required):
- `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
- `documentCriteria`* -- array, 1-50 items
- `documentCriteria[].shipmentPIN`* -- string

**Other accepted values you can send here:**
- `documentCriteria[].documentType[]` -- `CustomsInvoice` | `CustomsInvoiceThermal` | `DangerousGoodsDeclaration` | `DomesticBillOfLading` | `DomesticBillOfLadingThermal` | `ExpressChequeReceipt` | `ExpressChequeReceiptThermal` | `FDA2877` | `InternationalBillOfLading` | `InternationalBillOfLadingThermal` | `QRCode` | `USMCA`

**Conditional rules stated in the spec** (fields not sent here):
- `requestReference` -- A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
- `documentCriteria[].documentType` -- Optional filter. When omitted, eligible documents for the shipment are returned.

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN}}"
        },
        {
            "shipmentPIN": "{{PIN2}}"
        }
    ]
}
PURO_BODY,
], __FILE__);
