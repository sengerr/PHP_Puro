<?php
/**
 * 16 · Verified in Production 2026-11-06 / R-184 · Shipment Notification Receiver
 * POST {{baseUrl}}/ship/v1/shipment
 *
 * Run:  php requests/16-Verified-in-Production-2026-11-06/R-184-Shipment-Notification-Receiver.php [--dry-run] [--save]
 * Needs: REST_ClientId, REST_Secret, REST_XApiKey, AccountNumber (.env or environment)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Client.php';

return \Puro\Client::runIfMain([
    'folder' => '16 · Verified in Production 2026-11-06',
    'name'   => 'R-184 · Shipment Notification Receiver',
    'order'  => 2500,
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
Verified in Production 2026-11-06 — Purolator_Certification_Testing row R-184 (Shipment Notification Receiver).
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
        "3700 Jericho Road"
      ],
      "city": "Richmond",
      "provinceStateCode": "BC",
      "country": "CA",
      "postalZipCode": "V7B1M5",
      "phoneNumber": "18004595599"
    },
    "shipmentInformation": {
      "serviceId": "PurolatorExpress",
      "unitOfMeasurement": "IMPERIAL",
      "totalWeight": 1,
      "totalPackages": 1,
      "dangerousGoodsDeclarationDocumentIndicator": false,
      "packageInformation": [
        {
          "packageWeight": 1,
          "packageLength": 18.01,
          "packageWidth": 5,
          "packageHeight": 2
        }
      ]
    },
    "paymentInformation": {
      "paymentType": "Sender",
      "registeredAccountNumber": "{{AccountNumber}}"
    },
    "customerReferenceInformation": {
      "notes": "SH05   S15 = AA",
      "shipmentReference1": "TC # 005"
    },
    "notifications": {
      "requestorEmail": "myconf@abc.com",
      "subscriptions": [
        {
          "email": "myconf@abc.com",
          "notificationType": {
            "notificationId": "ShipmentNotification-Receiver",
            "notificationMessage": "Your shipment is on its way"
          },
          "name": "Tech A"
        }
      ]
    }
  },
  "shipmentDocuments": {
    "label": {
      "outputType": "ZPL",
      "printerFormat": "thermal",
      "responseType": "BASE64"
    }
  }
}
PURO_BODY,
], __FILE__);
