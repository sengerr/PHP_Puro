<?php
/**
 * 11 · Consolidation Check / 03 · Multipiece US Express (4 piece)
 * POST {{baseUrl}}{{createShipmentPath}}
 *
 * Run:  php requests/11-Consolidation-Check/03-Multipiece-US-Express-4-piece.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '11 · Consolidation Check',
    'name'   => '03 · Multipiece US Express (4 piece)',
    'order'  => 3000,
    'method' => 'POST',
    'url'    => '{{baseUrl}}{{createShipmentPath}}',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Consolidation check step 3 of 10.
Converted from the SOAP sample "Multipiece.txt". NOTE: that sample
  declares TotalPieces 4 but carries five Piece blocks, and the API
  returned five PINs. This request sends four, matching the step name.

<!-- developer-notes:begin (tooling/align_rest_to_spec.py; regenerated, do not edit) -->
### Developer notes
`POST /ship/v1/shipment` -- Create / Void Shipment spec (CreateShipmentRequest). Create a shipment

Create a courier outbound shipment, optional return shipment, or Returns Management (RMS) shipment. Freight is not supported.

**Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36

**Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `shipmentDocuments`, `shipmentDocuments.label`, `shipmentDocuments.label.outputType`, `outboundShipment`, `outboundShipment.paymentInformation`, `outboundShipment.paymentInformation.registeredAccountNumber`, `outboundShipment.paymentInformation.paymentType`, `outboundShipment.pickupType`, `outboundShipment.senderInformation`, `outboundShipment.senderInformation.city`, `outboundShipment.senderInformation.provinceStateCode`, `outboundShipment.senderInformation.country`, `outboundShipment.senderInformation.postalZipCode`, `outboundShipment.receiverInformation`, `outboundShipment.receiverInformation.city`, `outboundShipment.receiverInformation.country`, `outboundShipment.shipmentInformation`, `outboundShipment.shipmentInformation.serviceId`, `outboundShipment.shipmentInformation.totalWeight`, `outboundShipment.shipmentInformation.totalPackages`, `outboundShipment.shipmentInformation.packageInformation[].packageWeight`

**Fields in this body** (type, limits, accepted values; * = required):
- `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
- `shipmentDate` -- string (date). Date of shipment. Up to 10 days in advance. Date format: YYYY-MM-DD. If not present, defaults to today's date (America/Toronto).
- `validateOnly` -- boolean. When true, validates the request without creating a shipment or generating PINs.
- `returnsManagement` -- boolean. When true, outbound is treated as an RMS (returns management) shipment and returnShipment is ignored.
- `returnRates` -- boolean. When true, include rate estimates in the response. 930-division accounts (Account Lookup SALES_DISTRICT 930) also receive estimates when this flag is false. The flag is stored as sent.
- `outboundShipment.pickupType`* -- string; accepted: `PRESCHEDULED` | `DROPOFF`
- `outboundShipment.senderInformation.attentionTo` -- string, max 40 chars
- `outboundShipment.senderInformation.companyName` -- string, max 40 chars
- `outboundShipment.senderInformation.streetAddress` -- array, 1-3 items. Maximum 3 address lines. Each line has a max length of 40 characters.
- `outboundShipment.senderInformation.streetAddress[]` -- string, max 40 chars
- `outboundShipment.senderInformation.city`* -- string, max 40 chars
- `outboundShipment.senderInformation.provinceStateCode`* -- string, max 40 chars. 2-character province/state code. Required for Canadian and US addresses. Canadian provinces: AB, BC, MB, NB, NL, NS, NT, NU, ON, PE, QC, SK, YT. US states: AL, AK, AZ, AR, CA, CO, CT, DE, FL, GA, HI, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY.
- `outboundShipment.senderInformation.country`* -- string, max 2 chars; accepted: `CA`. 2-character country code. Sender must be CA.
- `outboundShipment.senderInformation.postalZipCode`* -- string, max 10 chars
- `outboundShipment.senderInformation.phoneNumber` -- string, max 15 chars. Phone number, 10-15 digits. Conditionally required.
- `outboundShipment.receiverInformation.attentionTo` -- string, max 40 chars
- `outboundShipment.receiverInformation.streetAddress` -- array, 1-3 items. Maximum 3 address lines. Each line has a max length of 40 characters.
- `outboundShipment.receiverInformation.streetAddress[]` -- string, max 40 chars
- `outboundShipment.receiverInformation.city`* -- string, max 40 chars
- `outboundShipment.receiverInformation.provinceStateCode` -- string, max 40 chars. 2-character province/state code. Required for Canadian and US addresses. Canadian provinces: AB, BC, MB, NB, NL, NS, NT, NU, ON, PE, QC, SK, YT. US states: AL, AK, AZ, AR, CA, CO, CT, DE, FL, GA, HI, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY.
- `outboundShipment.receiverInformation.country`* -- string, max 2 chars; accepted: one of 259 codes -- see `docs/REST-enumerations-and-accepted-values.md`. 2-character country code. Province and postal code are required for CA/US.
- `outboundShipment.receiverInformation.postalZipCode` -- string, max 10 chars
- `outboundShipment.receiverInformation.phoneNumber` -- string, max 15 chars. Phone number, 10-15 digits. Conditionally required.
- `outboundShipment.shipmentInformation.serviceId`* -- string; accepted: `PurolatorExpress` | `PurolatorExpress10:30AM` | `PurolatorExpress12PM` | `PurolatorExpress9AM` | `PurolatorExpressEvening` | `PurolatorExpressInternational` | `PurolatorExpressInternational10:30AM` | `PurolatorExpressInternational12PM` | `PurolatorExpressInternational9AM` | `PurolatorExpressU.S.` | `PurolatorExpressU.S.10:30AM` | `PurolatorExpressU.S.12PM` | `PurolatorExpressU.S.9AM` | `PurolatorExpressBox` | `PurolatorExpressBox10:30AM` | `PurolatorExpressBox12PM` | `PurolatorExpressBox9AM` | `PurolatorExpressBoxEvening` | `PurolatorExpressBoxInternational` | `PurolatorExpressBoxU.S.` | `PurolatorExpressBoxInternational10:30AM` | `PurolatorExpressBoxInternational12PM` | `PurolatorExpressBoxInternational9AM` | `PurolatorExpressBoxU.S.10:30AM` | `PurolatorExpressBoxU.S.12PM` | `PurolatorExpressBoxU.S.9AM` | `PurolatorExpressEnvelope` | `PurolatorExpressEnvelope10:30AM` | `PurolatorExpressEnvelope12PM` | `PurolatorExpressEnvelope9AM` | `PurolatorExpressEnvelopeEvening` | `PurolatorExpressEnvelopeInternational` | `PurolatorExpressEnvelopeU.S.` | `PurolatorExpressEnvelopeInternational10:30AM` | `PurolatorExpressEnvelopeInternational12PM` | `PurolatorExpressEnvelopeInternational9AM` | `PurolatorExpressEnvelopeU.S.10:30AM` | `PurolatorExpressEnvelopeU.S.12PM` | `PurolatorExpressEnvelopeU.S.9AM` | `PurolatorExpressPack` | `PurolatorExpressPack10:30AM` | `PurolatorExpressPack12PM` | `PurolatorExpressPack9AM` | `PurolatorExpressPackEvening` | `PurolatorExpressPackInternational` | `PurolatorExpressPackInternational10:30AM` | `PurolatorExpressPackInternational12PM` | `PurolatorExpressPackInternational9AM` | `PurolatorExpressPackU.S.` | `PurolatorExpressPackU.S.10:30AM` | `PurolatorExpressPackU.S.12PM` | `PurolatorExpressPackU.S.9AM` | `PurolatorGround` | `PurolatorGround10:30AM` | `PurolatorGround9AM` | `PurolatorGroundEvening` | `PurolatorGroundU.S.` | `PurolatorQuickShip` | `PurolatorStandard`
- `outboundShipment.shipmentInformation.unitOfMeasurement` -- string; accepted: `METRIC` | `IMPERIAL`. Imperial uses lb/in; metric uses kg/cm. Defaults to imperial if omitted.
- `outboundShipment.shipmentInformation.descriptionOfGoods` -- string, max 50 chars
- `outboundShipment.shipmentInformation.totalWeight`* -- number (double)
- `outboundShipment.shipmentInformation.totalPackages`* -- integer
- `outboundShipment.shipmentInformation.dangerousGoodsDeclarationDocumentIndicator` -- boolean, default false
- `outboundShipment.shipmentInformation.packageInformation` -- array
- `outboundShipment.shipmentInformation.packageInformation[].packageWeight`* -- number (double). Weight of the piece in the selected unit of measure (kg or lb). Must be greater than 0.
- `outboundShipment.shipmentInformation.packageInformation[].packageLength` -- number (double). Length of the piece (in or cm). If any dimension is supplied, length, width, and height are all required. Not required for Express Pack/Box/Envelope services.
- `outboundShipment.shipmentInformation.packageInformation[].packageWidth` -- number (double)
- `outboundShipment.shipmentInformation.packageInformation[].packageHeight` -- number (double)
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.importExportType` -- string; accepted: `Permanent` | `Temporary` | `Repair` | `Return`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.customsInvoiceDocumentIndicator` -- boolean
- `outboundShipment.crossBorderInformation.contentDetails` -- array. Required (non-empty) for non-domestic, non-documents-only shipments.
- `outboundShipment.crossBorderInformation.contentDetails[].description` -- string, max 50 chars
- `outboundShipment.crossBorderInformation.contentDetails[].harmonizedCode` -- string
- `outboundShipment.crossBorderInformation.contentDetails[].countryOfManufacture` -- string, max 2 chars
- `outboundShipment.crossBorderInformation.contentDetails[].unitValue` -- number (double), max 99999.99
- `outboundShipment.crossBorderInformation.contentDetails[].productCode` -- string
- `outboundShipment.crossBorderInformation.contentDetails[].quantity` -- integer
- `outboundShipment.crossBorderInformation.contentDetails[].usmcaDocumentIndicator` -- boolean
- `outboundShipment.crossBorderInformation.contentDetails[].fdaDocumentIndicator` -- boolean
- `outboundShipment.crossBorderInformation.contentDetails[].senderIsProducerIndicator` -- boolean
- `outboundShipment.crossBorderInformation.contentDetails[].textileIndicator` -- boolean
- `outboundShipment.paymentInformation.paymentType`* -- string; accepted: `SENDER` | `RECEIVER` | `THIRDPARTY`
- `outboundShipment.paymentInformation.registeredAccountNumber`* -- string, max 10 chars. Registered (shipper) account number. Numeric, max 10 digits.
- `outboundShipment.paymentInformation.billingAccountNumber` -- string, max 10 chars. Billing account number. Mandatory when paymentType is RECEIVER or THIRDPARTY. When paymentType is SENDER, if provided it must match registeredAccountNumber.
- `outboundShipment.customerReferenceInformation.costCenter` -- string, max 30 chars
- `outboundShipment.customerReferenceInformation.shipmentReference1` -- string, max 40 chars
- `shipmentDocuments.label.outputType`* -- string; accepted: `PDF` | `ZPL` | `DPL` | `PNG` | `CODE128`
- `shipmentDocuments.label.printerFormat` -- string; accepted: `Thermal` | `Regular` | `Laser`. Printer format. Optional for PNG/PDF/ZPL/DPL. Forced to NA for CODE128.
- `shipmentDocuments.label.responseType` -- string; accepted: `BASE64` | `URL` | `String`. How document content is returned. Mandatory for some output types.

**Other accepted values you can send here:**
- `outboundShipment.crossBorderInformation.currency` -- `CAD` | `USD`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.taxesPaid` -- `DDU` | `DDP`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.currencyForDutiableGoods` -- `CAD` | `USD` | `EUR`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.specialInstructionFlag` -- `Y` | `N`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.billDutiesToParty` -- `Sender` | `Receiver` | `Buyer`
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.businessRelationship` -- `TRUE` | `FALSE`

**Conditional rules stated in the spec** (fields not sent here):
- `outboundShipment.shipmentInformation.packageInformation[].brokeragePackageNumber` -- Optional ICP brokerage package number. Generated when omitted for ICP shipments whose brokerage provider is not No Broker/Other.
- `outboundShipment.customerReferenceInformation.costCenterReference` -- Optional. Alphanumeric only (A-Z, a-z, 0-9). No hyphens, spaces, or special characters. Max 20 characters.
- `outboundShipment.crossBorderInformation.documentsOnlyIndicator` -- When true, omitted, or null, non-document and content details are not required and taxesPaid defaults to DDU. Must not be present on domestic (CA to CA) shipments.
- `outboundShipment.crossBorderInformation.currency` -- Optional. Only CAD or USD are permitted for ICP Northbound shipments.
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.taxesPaid` -- Required for non-domestic, non-documents-only shipments.
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.businessRelationship` -- Required for non-domestic, non-documents-only shipments.
- `outboundShipment.crossBorderInformation.nonDocumentsInformation.brokerageReferenceNumber` -- Optional ICP shipment-level brokerage billing reference. Alphanumeric, no control characters. Ignored when brokerage provider is No Broker/Other.

Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
<!-- developer-notes:end -->
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
    "lineOfBusiness": "Courier",
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": false,
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "DROPOFF",
        "senderInformation": {
            "attentionTo": "Tech A",
            "companyName": "Purolator",
            "streetAddress": [
                "17718 114 Ave"
            ],
            "city": "Edmonton",
            "provinceStateCode": "AB",
            "country": "CA",
            "postalZipCode": "T5S2N4",
            "phoneNumber": "17804091270"
        },
        "receiverInformation": {
            "attentionTo": "Retail Agent",
            "streetAddress": [
                "2310 East Gladwick Street"
            ],
            "city": "Compton",
            "provinceStateCode": "CA",
            "country": "US",
            "postalZipCode": "90220",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpressU.S.",
            "unitOfMeasurement": "IMPERIAL",
            "descriptionOfGoods": "Consolidation check",
            "totalWeight": 50,
            "totalPackages": 4,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "packageInformation": [
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                },
                {
                    "packageWeight": 12.5,
                    "packageLength": 10,
                    "packageWidth": 10,
                    "packageHeight": 10
                }
            ]
        },
        "crossBorderInformation": {
            "nonDocumentsInformation": {
                "importExportType": "Permanent",
                "customsInvoiceDocumentIndicator": true
            },
            "contentDetails": [
                {
                    "description": "Wireless Router",
                    "harmonizedCode": "8517620090",
                    "countryOfManufacture": "CN",
                    "unitValue": 120,
                    "productCode": "WR-AX6000",
                    "quantity": 1,
                    "usmcaDocumentIndicator": false,
                    "fdaDocumentIndicator": false,
                    "senderIsProducerIndicator": true,
                    "textileIndicator": false
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "SENDER",
            "registeredAccountNumber": "{{AccountNumber}}",
            "billingAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "costCenter": "Consolidation",
            "shipmentReference1": "PO123456"
        }
    },
    "shipmentDocuments": {
        "label": {
            "outputType": "ZPL",
            "printerFormat": "Thermal",
            "responseType": "BASE64"
        }
    }
}
PURO_BODY,
], __FILE__);
