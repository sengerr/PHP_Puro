<?php
/**
 * DOCUMENTS · Batch 2 PINs
 *
 * POST /ship/v1/documents   (folder: 07 · Documents & Manifest)
 *
 * OAS: documentCriteria 1–50. Demo multi-PIN support workflow when Create omitted docs for size.
 *
 * Source: Purolator-REST-API-STG collection, "POST Documents - batch 2 PINs". REST only (no SOAP twin, 2026-10-06).
 *
 * ### Developer notes
 * `POST /ship/v1/documents` -- Get Documents spec (GetDocumentsRequest). Get shipment documents
 *
 * Retrieve documents for one or more shipment PINs. Freight is not supported.
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36
 *
 * **Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `documentCriteria`, `documentCriteria[].shipmentPIN`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
 * - `documentCriteria`* -- array, 1-50 items
 * - `documentCriteria[].shipmentPIN`* -- string
 *
 * **Other accepted values you can send here:**
 * - `documentCriteria[].documentType[]` -- `CustomsInvoice` | `CustomsInvoiceThermal` | `DangerousGoodsDeclaration` | `DomesticBillOfLading` | `DomesticBillOfLadingThermal` | `ExpressChequeReceipt` | `ExpressChequeReceiptThermal` | `FDA2877` | `InternationalBillOfLading` | `InternationalBillOfLadingThermal` | `QRCode` | `USMCA`
 *
 * **Conditional rules stated in the spec** (fields not sent here):
 * - `requestReference` -- A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
 * - `documentCriteria[].documentType` -- Optional filter. When omitted, eligible documents for the shipment are returned.
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/07-documents-and-manifest/documents-batch-2-pins.php [NAME=value ...]
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
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/documents', $body, $query, $headers));
