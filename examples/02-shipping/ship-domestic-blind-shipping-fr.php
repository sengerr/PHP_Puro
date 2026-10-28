<?php
/**
 * SHIP · Domestic · Blind Shipping · FR
 *
 * POST /ship/v1/shipment   (folder: 02 · Shipping)
 *
 * **Spec-aligned 2026-10-24, not yet verified live** -- changed to match the published OpenAPI spec after it failed (or was not sent) in the 2026-10-06 run.
 *
 * French twin of “REST-Create Shipment-Blind Shipping” — identical except the Language header, which is
 * hard-coded to fr. A document's language is fixed when the SHIPMENT is
 * created: the same PIN returns the same bytes whether /ship/v1/documents
 * is called with en or fr, so French coverage needs its own create call.
 *
 * Converted from SOAP ShippingServiceEndpoint.CreateShipment — test-aid case “09.5 Domestic Shipment - Blind Shipping”.
 *
 * ### Developer notes
 * `POST /ship/v1/shipment` -- Create / Void Shipment spec (CreateShipmentRequest). Create a shipment
 *
 * Create a courier outbound shipment, optional return shipment, or Returns Management (RMS) shipment. Freight is not supported.
 *
 * **Headers:** Authorization Bearer `{{REST_Token}}` · `x-api-key` (required) · `Language` (optional) `EN` | `FR` · `RequestReference` (optional) max 36
 *
 * **Required fields** (top level, and inside every object this body sends): `lineOfBusiness`, `shipmentDocuments`, `shipmentDocuments.label`, `shipmentDocuments.label.outputType`, `outboundShipment`, `outboundShipment.paymentInformation`, `outboundShipment.paymentInformation.registeredAccountNumber`, `outboundShipment.paymentInformation.paymentType`, `outboundShipment.pickupType`, `outboundShipment.senderInformation`, `outboundShipment.senderInformation.city`, `outboundShipment.senderInformation.provinceStateCode`, `outboundShipment.senderInformation.country`, `outboundShipment.senderInformation.postalZipCode`, `outboundShipment.receiverInformation`, `outboundShipment.receiverInformation.city`, `outboundShipment.receiverInformation.country`, `outboundShipment.fromOnLabelInformation.city`, `outboundShipment.fromOnLabelInformation.provinceStateCode`, `outboundShipment.fromOnLabelInformation.country`, `outboundShipment.fromOnLabelInformation.postalZipCode`, `outboundShipment.shipmentInformation`, `outboundShipment.shipmentInformation.serviceId`, `outboundShipment.shipmentInformation.totalWeight`, `outboundShipment.shipmentInformation.totalPackages`, `outboundShipment.shipmentInformation.packageInformation[].packageWeight`, `outboundShipment.notifications.subscriptions[].email`, `outboundShipment.notifications.subscriptions[].notificationType.notificationId`
 *
 * **Fields in this body** (type, limits, accepted values; * = required):
 * - `lineOfBusiness`* -- string; accepted: `Courier` | `Freight`. Line of business. Case-insensitive. Only Courier is supported.
 * - `shipmentDate` -- string (date). Date of shipment. Up to 10 days in advance. Date format: YYYY-MM-DD. If not present, defaults to today's date (America/Toronto).
 * - `validateOnly` -- boolean. When true, validates the request without creating a shipment or generating PINs.
 * - `returnsManagement` -- boolean. When true, outbound is treated as an RMS (returns management) shipment and returnShipment is ignored.
 * - `returnRates` -- boolean. When true, include rate estimates in the response. 930-division accounts (Account Lookup SALES_DISTRICT 930) also receive estimates when this flag is false. The flag is stored as sent.
 * - `outboundShipment.pickupType`* -- string; accepted: `PRESCHEDULED` | `DROPOFF`
 * - `outboundShipment.senderInformation.attentionTo` -- string, max 40 chars
 * - `outboundShipment.senderInformation.streetAddress` -- array, 1-3 items. Maximum 3 address lines. Each line has a max length of 40 characters.
 * - `outboundShipment.senderInformation.streetAddress[]` -- string, max 40 chars
 * - `outboundShipment.senderInformation.city`* -- string, max 40 chars
 * - `outboundShipment.senderInformation.provinceStateCode`* -- string, max 40 chars. 2-character province/state code. Required for Canadian and US addresses. Canadian provinces: AB, BC, MB, NB, NL, NS, NT, NU, ON, PE, QC, SK, YT. US states: AL, AK, AZ, AR, CA, CO, CT, DE, FL, GA, HI, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY.
 * - `outboundShipment.senderInformation.country`* -- string, max 2 chars; accepted: `CA`. 2-character country code. Sender must be CA.
 * - `outboundShipment.senderInformation.postalZipCode`* -- string, max 10 chars
 * - `outboundShipment.senderInformation.phoneNumber` -- string, max 15 chars. Phone number, 10-15 digits. Conditionally required.
 * - `outboundShipment.receiverInformation.attentionTo` -- string, max 40 chars
 * - `outboundShipment.receiverInformation.streetAddress` -- array, 1-3 items. Maximum 3 address lines. Each line has a max length of 40 characters.
 * - `outboundShipment.receiverInformation.streetAddress[]` -- string, max 40 chars
 * - `outboundShipment.receiverInformation.city`* -- string, max 40 chars
 * - `outboundShipment.receiverInformation.provinceStateCode` -- string, max 40 chars. 2-character province/state code. Required for Canadian and US addresses. Canadian provinces: AB, BC, MB, NB, NL, NS, NT, NU, ON, PE, QC, SK, YT. US states: AL, AK, AZ, AR, CA, CO, CT, DE, FL, GA, HI, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY.
 * - `outboundShipment.receiverInformation.country`* -- string, max 2 chars; accepted: one of 259 codes -- see `docs/REST-enumerations-and-accepted-values.md`. 2-character country code. Province and postal code are required for CA/US.
 * - `outboundShipment.receiverInformation.postalZipCode` -- string, max 10 chars
 * - `outboundShipment.receiverInformation.phoneNumber` -- string, max 15 chars. Phone number, 10-15 digits. Conditionally required.
 * - `outboundShipment.shipmentInformation.serviceId`* -- string; accepted: `PurolatorExpress` | `PurolatorExpress10:30AM` | `PurolatorExpress12PM` | `PurolatorExpress9AM` | `PurolatorExpressEvening` | `PurolatorExpressInternational` | `PurolatorExpressInternational10:30AM` | `PurolatorExpressInternational12PM` | `PurolatorExpressInternational9AM` | `PurolatorExpressU.S.` | `PurolatorExpressU.S.10:30AM` | `PurolatorExpressU.S.12PM` | `PurolatorExpressU.S.9AM` | `PurolatorExpressBox` | `PurolatorExpressBox10:30AM` | `PurolatorExpressBox12PM` | `PurolatorExpressBox9AM` | `PurolatorExpressBoxEvening` | `PurolatorExpressBoxInternational` | `PurolatorExpressBoxU.S.` | `PurolatorExpressBoxInternational10:30AM` | `PurolatorExpressBoxInternational12PM` | `PurolatorExpressBoxInternational9AM` | `PurolatorExpressBoxU.S.10:30AM` | `PurolatorExpressBoxU.S.12PM` | `PurolatorExpressBoxU.S.9AM` | `PurolatorExpressEnvelope` | `PurolatorExpressEnvelope10:30AM` | `PurolatorExpressEnvelope12PM` | `PurolatorExpressEnvelope9AM` | `PurolatorExpressEnvelopeEvening` | `PurolatorExpressEnvelopeInternational` | `PurolatorExpressEnvelopeU.S.` | `PurolatorExpressEnvelopeInternational10:30AM` | `PurolatorExpressEnvelopeInternational12PM` | `PurolatorExpressEnvelopeInternational9AM` | `PurolatorExpressEnvelopeU.S.10:30AM` | `PurolatorExpressEnvelopeU.S.12PM` | `PurolatorExpressEnvelopeU.S.9AM` | `PurolatorExpressPack` | `PurolatorExpressPack10:30AM` | `PurolatorExpressPack12PM` | `PurolatorExpressPack9AM` | `PurolatorExpressPackEvening` | `PurolatorExpressPackInternational` | `PurolatorExpressPackInternational10:30AM` | `PurolatorExpressPackInternational12PM` | `PurolatorExpressPackInternational9AM` | `PurolatorExpressPackU.S.` | `PurolatorExpressPackU.S.10:30AM` | `PurolatorExpressPackU.S.12PM` | `PurolatorExpressPackU.S.9AM` | `PurolatorGround` | `PurolatorGround10:30AM` | `PurolatorGround9AM` | `PurolatorGroundEvening` | `PurolatorGroundU.S.` | `PurolatorQuickShip` | `PurolatorStandard`
 * - `outboundShipment.shipmentInformation.unitOfMeasurement` -- string; accepted: `METRIC` | `IMPERIAL`. Imperial uses lb/in; metric uses kg/cm. Defaults to imperial if omitted.
 * - `outboundShipment.shipmentInformation.totalWeight`* -- number (double)
 * - `outboundShipment.shipmentInformation.totalPackages`* -- integer
 * - `outboundShipment.shipmentInformation.dangerousGoodsDeclarationDocumentIndicator` -- boolean, default false
 * - `outboundShipment.shipmentInformation.shipmentOptionsInformation` -- array
 * - `outboundShipment.shipmentInformation.shipmentOptionsInformation[].optionId` -- string, max 30 chars; accepted: `AdultSignatureRequired` | `ChainOfSignature` | `DangerousGoods` | `DangerousGoodsClass` | `DangerousGoodsMode` | `DeclaredValue` | `ExpressCheque` | `ExpressChequeMethodOfPayment` | `ExpressChequeAmount` | `HealthCare` | `HoldForPickup` | `OriginSignatureNotRequired` | `ResidentialSignatureDomestic` | `ResidentialSignatureIntl` | `SaturdayDelivery` | `SaturdayPickup` | `SundayDelivery` | `SundayPickup` | `ReturnPickup` | `PackageFree` | `OpenVerify`
 * - `outboundShipment.shipmentInformation.shipmentOptionsInformation[].optionIdValue` -- string, max 30 chars. Value for the option. Conditionally required depending on optionId. Typical values: true, false, Air, Ground, FullyRegulated, UN3373, UN1845, LessThan500kgExempt, LimitedQuantities. ExpressChequeAmount is numeric.
 * - `outboundShipment.shipmentInformation.packageInformation` -- array
 * - `outboundShipment.shipmentInformation.packageInformation[].packageWeight`* -- number (double). Weight of the piece in the selected unit of measure (kg or lb). Must be greater than 0.
 * - `outboundShipment.shipmentInformation.packageInformation[].packageLength` -- number (double). Length of the piece (in or cm). If any dimension is supplied, length, width, and height are all required. Not required for Express Pack/Box/Envelope services.
 * - `outboundShipment.shipmentInformation.packageInformation[].packageWidth` -- number (double)
 * - `outboundShipment.shipmentInformation.packageInformation[].packageHeight` -- number (double)
 * - `outboundShipment.paymentInformation.paymentType`* -- string; accepted: `SENDER` | `RECEIVER` | `THIRDPARTY`
 * - `outboundShipment.paymentInformation.registeredAccountNumber`* -- string, max 10 chars. Registered (shipper) account number. Numeric, max 10 digits.
 * - `outboundShipment.customerReferenceInformation.notes` -- string, max 70 chars
 * - `outboundShipment.customerReferenceInformation.shipmentReference1` -- string, max 40 chars
 * - `outboundShipment.notifications.requestorEmail` -- string (email), max 70 chars. Required when subscriptions are present.
 * - `outboundShipment.notifications.subscriptions` -- array
 * - `outboundShipment.notifications.subscriptions[].email`* -- string (email), max 70 chars
 * - `outboundShipment.notifications.subscriptions[].notificationType.notificationId`* -- string; accepted: `NotifyWhenExceptionOccurs` | `NotifyWhenDeliveryOccurs` | `ShipmentConfirmation-Requester` | `ShipmentNotification-Receiver`
 * - `outboundShipment.notifications.subscriptions[].name` -- string, max 70 chars. Mandatory when notificationId is NotifyWhenExceptionOccurs, NotifyWhenDeliveryOccurs, ShipmentConfirmation-Requester, or ShipmentNotification-Receiver.
 * - `outboundShipment.fromOnLabelInformation.attentionTo` -- string, max 40 chars
 * - `outboundShipment.fromOnLabelInformation.companyName` -- string, max 40 chars
 * - `outboundShipment.fromOnLabelInformation.streetAddress` -- array, 1-3 items. Maximum 3 address lines. Each line has a max length of 40 characters.
 * - `outboundShipment.fromOnLabelInformation.streetAddress[]` -- string, max 40 chars
 * - `outboundShipment.fromOnLabelInformation.city`* -- string, max 40 chars
 * - `outboundShipment.fromOnLabelInformation.provinceStateCode`* -- string, max 40 chars. 2-character province/state code. Required for Canadian and US addresses. Canadian provinces: AB, BC, MB, NB, NL, NS, NT, NU, ON, PE, QC, SK, YT. US states: AL, AK, AZ, AR, CA, CO, CT, DE, FL, GA, HI, ID, IL, IN, IA, KS, KY, LA, ME, MD, MA, MI, MN, MS, MO, MT, NE, NV, NH, NJ, NM, NY, NC, ND, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VT, VA, WA, WV, WI, WY.
 * - `outboundShipment.fromOnLabelInformation.country`* -- string, max 2 chars; accepted: one of 259 codes -- see `docs/REST-enumerations-and-accepted-values.md`
 * - `outboundShipment.fromOnLabelInformation.postalZipCode`* -- string, max 10 chars
 * - `outboundShipment.fromOnLabelInformation.phoneNumber` -- string, max 15 chars. Phone number, 10-15 digits. Conditionally required.
 * - `outboundShipment.fromOnLabelInformation.fromOnLabelIndicator` -- boolean. When true, from-on-label address fields are required.
 * - `shipmentDocuments.label.outputType`* -- string; accepted: `PDF` | `ZPL` | `DPL` | `PNG` | `CODE128`
 * - `shipmentDocuments.label.printerFormat` -- string; accepted: `Thermal` | `Regular` | `Laser`. Printer format. Optional for PNG/PDF/ZPL/DPL. Forced to NA for CODE128.
 * - `shipmentDocuments.label.responseType` -- string; accepted: `BASE64` | `URL` | `String`. How document content is returned. Mandatory for some output types.
 *
 * **Conditional rules stated in the spec** (fields not sent here):
 * - `outboundShipment.paymentInformation.billingAccountNumber` -- Billing account number. Mandatory when paymentType is RECEIVER or THIRDPARTY. When paymentType is SENDER, if provided it must match registeredAccountNumber.
 * - `outboundShipment.shipmentInformation.packageInformation[].brokeragePackageNumber` -- Optional ICP brokerage package number. Generated when omitted for ICP shipments whose brokerage provider is not No Broker/Other.
 * - `outboundShipment.customerReferenceInformation.costCenterReference` -- Optional. Alphanumeric only (A-Z, a-z, 0-9). No hyphens, spaces, or special characters. Max 20 characters.
 * - `outboundShipment.notifications.subscriptions[].notificationType.notificationMessage` -- Mandatory when notificationId is ShipmentNotification-Receiver.
 *
 * Full value lists: `docs/REST-enumerations-and-accepted-values.md`.
 *
 * Run:  php examples/02-shipping/ship-domestic-blind-shipping-fr.php [NAME=value ...]
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
    "shipmentDate": "{{Current_Date}}",
    "validateOnly": false,
    "returnsManagement": false,
    "returnRates": false,
    "outboundShipment": {
        "pickupType": "DROPOFF",
        "senderInformation": {
            "attentionTo": "Tech A",
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
            "attentionTo": "Home Receiver",
            "streetAddress": [
                "24 Sussex Drive"
            ],
            "city": "Ottawa",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "K1M1P5",
            "phoneNumber": "18004595599"
        },
        "shipmentInformation": {
            "serviceId": "PurolatorExpress",
            "unitOfMeasurement": "IMPERIAL",
            "totalWeight": 1,
            "totalPackages": 1,
            "dangerousGoodsDeclarationDocumentIndicator": false,
            "shipmentOptionsInformation": [
                {
                    "optionId": "ResidentialSignatureDomestic",
                    "optionIdValue": "true"
                }
            ],
            "packageInformation": [
                {
                    "packageWeight": 1,
                    "packageLength": 18.01,
                    "packageWidth": 5,
                    "packageHeight": 12
                }
            ]
        },
        "paymentInformation": {
            "paymentType": "SENDER",
            "registeredAccountNumber": "{{AccountNumber}}"
        },
        "customerReferenceInformation": {
            "notes": "Blind Shipping",
            "shipmentReference1": "TC # 001"
        },
        "notifications": {
            "requestorEmail": "myconf@abc.com",
            "subscriptions": [
                {
                    "email": "myconf@abc.com",
                    "notificationType": {
                        "notificationId": "ShipmentConfirmation-Requester"
                    },
                    "name": "Tech A"
                }
            ]
        },
        "fromOnLabelInformation": {
            "attentionTo": "Blind Name",
            "companyName": "Blind Company",
            "streetAddress": [
                "321 Blind Street Name",
                "Blind Add 2",
                "Blind Add 3"
            ],
            "city": "ETOBICOKE",
            "provinceStateCode": "ON",
            "country": "CA",
            "postalZipCode": "M9W4W7",
            "phoneNumber": "11231234567",
            "fromOnLabelIndicator": true
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
JSON_BODY;

$body = PurolatorClient::fill($template, $vars, true);
$query = [];
$headers = [PurolatorClient::fill('Language: fr', $vars)];

$client = new PurolatorClient();                   // tier from PUROLATOR_ENV
PurolatorClient::show($client->request('POST', '/ship/v1/shipment', $body, $query, $headers));
