<?php
/**
 * DOC · INTL · InternationalBillOfLading · PDF · Laser · BASE64 · FR
 *
 * POST /ship/v1/documents   (folder: 14 · Document Matrix)
 *
 * French twin of “DOC · INTL · InternationalBillOfLading · PDF · Laser · BASE64” — same call, pointed at the FRENCH
 * seed's PIN. The document call does not choose a language: the same PIN
 * returns the same bytes in en or fr. The seed decides. Language: fr is
 * sent so any ERROR comes back in French, which is the one thing the
 * retrieval header does change.
 * Document matrix: InternationalBillOfLading from the International seed created as PDF / Laser / BASE64.
 * Reads {{PIN_INTL_PDF_BASE64_FR}}. documentCriteria carries only shipmentPIN and
 * documentType -- every format field is rejected as unknown here;
 * the format came from how the seed was created.
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
 * - `requestReference` -- string, max 36 chars. A unique identifier for the request. Can be used to match request with the response. If not provided, a random UUID is generated.
 * - `documentCriteria`* -- array, 1-50 items
 * - `documentCriteria[].shipmentPIN`* -- string
 * - `documentCriteria[].documentType` -- array. Optional filter. When omitted, eligible documents for the shipment are returned.
 * - `documentCriteria[].documentType[]` -- string; accepted: `CustomsInvoice` | `CustomsInvoiceThermal` | `DangerousGoodsDeclaration` | `DomesticBillOfLading` | `DomesticBillOfLadingThermal` | `ExpressChequeReceipt` | `ExpressChequeReceiptThermal` | `FDA2877` | `InternationalBillOfLading` | `InternationalBillOfLadingThermal` | `QRCode` | `USMCA`
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/14-document-matrix/doc-intl-internationalbilloflading-pdf-laser-base64-fr.php [NAME=value ...]
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
    "requestReference": "{{RequestReference}}",
    "documentCriteria": [
        {
            "shipmentPIN": "{{PIN_INTL_PDF_BASE64_FR}}",
            "documentType": [
                "InternationalBillOfLading"
            ]
        }
    ]
}
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, false);
$query = [];
$headers = [PurolatorClient::fill('Language: fr', $vars)];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/documents', $body, $query, $headers));
