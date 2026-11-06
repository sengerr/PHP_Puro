<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-132 · Certified Cheque
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-132-Certified-Cheque.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-132 · Certified Cheque',
    'order'  => 1100,
    'method' => 'POST',
    'url'    => '{{baseUrl}}/ship/v1/shipment',
    'headers' => [
        'x-api-key' => '{{REST_XApiKey}}',
        'RequestReference' => '{{RequestReference}}',
        'Language' => '{{language}}',
        'Content-Type' => 'application/json',
    ],
    'auth'   => 'bearer',
    'description' => <<<'PURO_TEXT'
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-132 (Certified Cheque).
Expected outcome: HTTP 200 · validShipment: true · shipmentPIN returned · packagePINs returned · documentStatus: Completed · banner: "Quote is subject to change based on actual shipment characte…"
Placeholders in the body: AccountNumber, Current_Date.
Production calls create real shipments and pickups — void anything you create.
PURO_TEXT,
    'body' => <<<'PURO_BODY'
{
  "lineOfBusiness": "COURIER",
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
      "attentionTo": "Tech B",
      "companyName": "Purolator",
      "streetAddress": [
        "30 Aero Drive NE"
      ],
      "city": "Calgary",
      "provinceStateCode": "AB",
      "country": "CA",
      "postalZipCode": "T2E8Z9",
      "phoneNumber": "18004595599"
    },
    "shipmentInformation": {
      "serviceId": "PurolatorGround",
      "unitOfMeasurement": "IMPERIAL",
      "totalWeight": 150,
      "totalPackages": 1,
      "dangerousGoodsDeclarationDocumentIndicator": false,
      "shipmentOptionsInformation": [
        {
          "optionId": "DangerousGoods",
          "optionIdValue": "false"
        },
        {
          "optionId": "DangerousGoodsClass",
          "optionIdValue": "FullyRegulated"
        },
        {
          "optionId": "DangerousGoodsMode",
          "optionIdValue": "Ground"
        },
        {
          "optionId": "DeclaredValue",
          "optionIdValue": "100.00"
        },
        {
          "optionId": "ChainOfSignature",
          "optionIdValue": "false"
        },
        {
          "optionId": "SaturdayPickup",
          "optionIdValue": "false"
        },
        {
          "optionId": "SaturdayDelivery",
          "optionIdValue": "false"
        },
        {
          "optionId": "ExpressCheque",
          "optionIdValue": "false"
        },
        {
          "optionId": "ExpressChequeMethodOfPayment",
          "optionIdValue": "Certified Cheque"
        },
        {
          "optionId": "ExpressChequeAmount",
          "optionIdValue": "100.00"
        },
        {
          "optionId": "HoldForPickup",
          "optionIdValue": "false"
        },
        {
          "optionId": "OriginSignatureNotRequired",
          "optionIdValue": "false"
        },
        {
          "optionId": "ResidentialSignatureDomestic",
          "optionIdValue": "false"
        },
        {
          "optionId": "ResidentialSignatureIntl",
          "optionIdValue": "false"
        },
        {
          "optionId": "AdultSignatureRequired",
          "optionIdValue": "false"
        },
        {
          "optionId": "HealthCare",
          "optionIdValue": "false"
        }
      ],
      "packageInformation": [
        {
          "packageWeight": 75,
          "packageLength": 12,
          "packageWidth": 12,
          "packageHeight": 1
        },
        {
          "packageWeight": 75,
          "packageLength": 12,
          "packageWidth": 12,
          "packageHeight": 1
        }
      ]
    },
    "paymentInformation": {
      "paymentType": "Sender",
      "registeredAccountNumber": "{{AccountNumber}}",
      "billingAccountNumber": "{{AccountNumber}}"
    },
    "customerReferenceInformation": {
      "notes": "East"
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
