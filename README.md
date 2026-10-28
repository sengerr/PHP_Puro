# PHP_Puro: Purolator REST API examples in plain PHP

These are PHP versions of every REST request in the [Purolator_2026](https://github.com/sengerr/Purolator_2026) Postman collections. Each request has one script, and there are 420 in total.

- **Plain PHP 8.1+ and the cURL extension.** You do not need Composer or any packages.
- **One client class:** `src/PurolatorClient.php`. It fetches the token and sets the headers (`Authorization: Bearer`, `x-api-key`, `RequestReference`, `Language`).
- **One script per request** in `examples/<folder>/`. The folders match the Postman folders. [`examples/INDEX.md`](examples/INDEX.md) lists every script with its request name and endpoint.
- **One script for both tiers.** `PUROLATOR_ENV` chooses Development or Production, so there is no separate copy per tier.
- **Credentials come only from environment variables.** No key, secret, token or account number is written in any file.

| `PUROLATOR_ENV` | Host | OAuth scope |
|---|---|---|
| `dev` (default) | `https://shipapi-sandbox.purolator.com` | `portal_api_sandbox` |
| `prod` | `https://shipapi.purolator.com` | `portal_api` |

> **Production creates real shipments, pickups and charges.** Use `dev` until your integration is certified.

---

## Set up on Windows

### 1. Install PHP

1. Download PHP 8.1 or later (x64, *Non Thread Safe*) from <https://windows.php.net/download/>.
2. Unzip it to a folder such as `C:\php`.
3. Add that folder to your `PATH`. Open a new terminal afterwards so it picks up the change.

   ```powershell
   setx PATH "$env:PATH;C:\php"
   ```

4. Check the version:

   ```powershell
   php -v
   ```

### 2. Turn on the cURL extension (php.ini)

1. In `C:\php`, copy `php.ini-production` to `php.ini`.
2. Open `php.ini` and remove the leading `;` from these lines:

   ```ini
   extension_dir = "ext"
   extension=curl
   extension=openssl
   ```

3. HTTPS needs a CA bundle. Download `cacert.pem` from <https://curl.se/docs/caextract.html> and save it as `C:\php\extras\ssl\cacert.pem`. Then set both of these lines in `php.ini`:

   ```ini
   curl.cainfo = "C:\php\extras\ssl\cacert.pem"
   openssl.cafile = "C:\php\extras\ssl\cacert.pem"
   ```

4. Confirm that cURL is loaded:

   ```powershell
   php -m | findstr curl
   ```

### 3. Set your credentials as environment variables

`setx` stores a variable for your user. Only terminals you open **afterwards** can see it.

```powershell
setx PUROLATOR_ENV           dev
setx PUROLATOR_CLIENT_ID     "<your Okta client id>"
setx PUROLATOR_CLIENT_SECRET "<your Okta client secret>"
setx PUROLATOR_X_API_KEY     "<your x-api-key>"
setx PUROLATOR_ACCOUNT       "<your account number>"
```

Purolator issues these values per customer. To set them for the current window only, without saving them, use `$env:PUROLATOR_ENV = "dev"` and so on.

| Variable | Required | What it is |
|---|---|---|
| `PUROLATOR_ENV` | no (default `dev`) | `dev` or `prod` |
| `PUROLATOR_CLIENT_ID`, `PUROLATOR_CLIENT_SECRET` | yes | Okta client credentials, used for `POST /auth/v1/token` |
| `PUROLATOR_X_API_KEY` | yes | the `x-api-key` header, sent on every call |
| `PUROLATOR_ACCOUNT` | for most calls | your billing / registered account (fills `{{AccountNumber}}`) |
| `PUROLATOR_ACCOUNT_2` | third-party / receiver billing only | a second account (`{{Different_AccountNumber}}`) |
| `PUROLATOR_TOKEN` | no | a bearer token you already have; skips the token call |
| `PUROLATOR_LANGUAGE` | no (default `en`) | `en` or `fr` for response text |
| `PUROLATOR_SHIP_DAYS_AHEAD` | no (default `5`) | create / validate shipment date = today + N days |
| `PUROLATOR_VAR_<NAME>` | when a script asks | the value for any other `{{NAME}}` placeholder, e.g. `PUROLATOR_VAR_PIN` |

### 4. Run a script

```powershell
cd PHP_Puro
php examples\00-auth\auth-okta-token.php          # check your credentials
php examples\01-estimate\estimate-domestic-basic.php
php examples\05-tracking\track-by-pin.php trackingId=329016612345
```

Each script prints the HTTP status and the JSON response.

---

## How the examples work

Each script keeps the request body **exactly as it is in the Postman collection**. The body is a JSON template, and its `{{placeholders}}` are filled when the script runs:

- `{{AccountNumber}}`, `{{Different_AccountNumber}}`: from `PUROLATOR_ACCOUNT` / `PUROLATOR_ACCOUNT_2`.
- `{{Current_Date}}`: today in Purolator's timezone (America/Toronto). Create and validate requests use today + 5 days instead, the same as the Postman pre-request script.
- `{{manifestDate}}`: yesterday. The summary manifest only accepts dates from today minus 10 days through today.
- `{{RequestReference}}`: `REQ` + a timestamp. `{{language}}`: `PUROLATOR_LANGUAGE`.
- Anything else, such as `{{PIN}}`, `{{trackingId}}` or `{{PIN_DOM_ZPL_URL}}`: pass it as `NAME=value` after the script name, or set `PUROLATOR_VAR_NAME`. The script stops and names any value it is missing.

**PINs come from earlier calls.** In Postman, a create-shipment request saves the PIN that later document, tracking and void requests use. In PHP, you run the create script, copy `shipmentPIN` from its response, and pass that value to the next script.

The doc block at the top of each script carries the request's notes from the collection. These include the *Developer notes*: the endpoint, headers, required fields, and for every field the body sends, its type, length limit and accepted values, plus the conditional rules from Purolator's OpenAPI spec. For the complete value lists, see [`docs/REST-enumerations-and-accepted-values.md`](https://github.com/sengerr/Purolator_2026/blob/main/docs/REST-enumerations-and-accepted-values.md) in Purolator_2026.

### Using the client in your own code

```php
<?php
require 'src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

$client = new PurolatorClient();            // tier from PUROLATOR_ENV
$res = $client->request('PUT', '/ship/v1/shipment', [
    'lineOfBusiness' => 'Courier',
    'shipmentPIN'    => '329016612345',
]);                                         // void a shipment
echo $res['status'], PHP_EOL;               // 200
print_r($res['json']);                      // ['shipmentVoided' => 'true', ...]
```

`request()` returns `status`, `json` (the decoded body), `body` (the raw text) and `headers`. If a cached token is rejected with a 401, it fetches a new one and retries once.

---

## What is included

| Folder | Scripts | Endpoint |
|---|---|---|
| `00-auth` | 1 | `POST /auth/v1/token` |
| `01-estimate` | 60 | `POST /rate/v1/shipment` |
| `02-shipping` | 97 | `POST /ship/v1/shipment` (create / validate), `PUT /ship/v1/shipment` (void) |
| `03-returns-management` | 17 | `POST /ship/v1/shipment` |
| `05-tracking` | 19 | `POST /track/v1/shipment` |
| `06-locator` | 4 | `GET /locator/v1/address` |
| `07-documents-and-manifest` | 15 | `POST /ship/v1/documents`, `POST /ship/v1/documents/summary-manifest`, `POST /ship/v1/manifest` |
| `08`-`10` product matrix | 100 | `POST /ship/v1/shipment`, one per service |
| `11-consolidation-check` | 9 | create shipments, then close the manifest |
| `12-payment-types` | 12 | `POST /ship/v1/shipment`, with sender / receiver / third-party billing |
| `13-document-seeds`, `14-document-matrix` | 86 | create a shipment in each label format, then fetch each document type |

Pickups (`/pickup/v1/*`) are not included, because Purolator_2026 does not carry them yet. The scripts are generated from Purolator_2026, so the two repositories always hold the same requests. Thirteen of these requests are marked *Spec-aligned 2026-10-24, not yet verified live* in their doc block.

## Regenerating

The examples are generated, so do not edit them by hand. The generator is in the internal Purolator_TC_EWS repository:

```powershell
python tooling\build_php_repo.py ..\Purolator_2026 ..\PHP_Puro
```

It rebuilds `examples/` from scratch. It does not touch `src/` or this README.

---

# Second script set: `requests/`, Postman collections and tests

This repository holds two script sets generated from the same Purolator_2026 requests (420 each). `examples/` and `src/PurolatorClient.php` are described above. The set below adds a second client, ready-made Postman collections, and tests.

| | `examples/` (above) | `requests/` (this section) |
|---|---|---|
| Client | `src/PurolatorClient.php` | `src/Client.php` |
| Settings | environment variables `PUROLATOR_*` | the same `PUROLATOR_*` names, **or** the Postman names (`REST_ClientId`, ...), **or** a git-ignored `.env` file |
| Token and PINs | token cached; PINs passed by hand (`NAME=value`) | token cached; shipment PINs saved in `.state.json` and read by later scripts |
| Extras | `INDEX.md` | `bin/run-folder.php`, Postman collections, tests |

Both sets use the same variable names, so credentials are set once.

## Run a `requests/` script

```bat
:: Print the request without sending it (credentials are hidden)
php requests\02-Shipping\SHIP-Domestic-Basic.php --dry-run

:: Send it
php requests\02-Shipping\SHIP-Domestic-Basic.php

:: Send it and keep the full response (labels are long base64 strings) in output\
php requests\02-Shipping\SHIP-Domestic-Basic.php --save

:: Fill a placeholder by hand
php requests\05-Tracking\TRACK-V2-Reference.php referenceData=ABC123

:: Run a whole folder, top to bottom, in the original Postman order
php bin\run-folder.php 02-Shipping
```

Settings can come from your environment or from a `.env` file (copy `.env.example`). Real environment variables win over `.env`.

| Setting | Also accepted as | What it is |
|---|---|---|
| `PUROLATOR_ENV` | | `dev` (default) or `prod` |
| `REST_ClientId`, `REST_Secret` | `PUROLATOR_CLIENT_ID`, `PUROLATOR_CLIENT_SECRET` | Okta client credentials |
| `REST_XApiKey` | `PUROLATOR_X_API_KEY` | the `x-api-key` header |
| `AccountNumber`, `Different_AccountNumber` | `PUROLATOR_ACCOUNT`, `PUROLATOR_ACCOUNT_2` | billing accounts |
| `REST_Token` | `PUROLATOR_TOKEN` | a token you already have; skips the token call |
| `shipDaysAhead` | `PUROLATOR_SHIP_DAYS_AHEAD` | days added to the shipment date of create / validate requests (default 5; `0` = ship today) |
| any `{{NAME}}` | `PUROLATOR_VAR_NAME`, or `NAME=value` after the script name | for values only you can supply, such as `trackingId2` |
| `PUROLATOR_CAINFO` | | path to `cacert.pem` if HTTPS fails on Windows |

How the scripts behave:

- **Token.** The first request gets an OAuth token and caches it in `.state.json` until shortly before it expires.
- **Dates.** `Current_Date` and the other date variables are computed in Purolator's time zone (America/Toronto), not yours.
- **PINs.** A create-shipment response gives the PIN that later document, void and tracking requests read. It is saved in `.state.json` under the same variable names the Postman collections use (`PIN`, `PIN_VOID_1`, `PIN_DomesticBillOfLading`, ...). Run a folder top to bottom the first time.
- **Exit code.** 0 for HTTP 2xx, 1 for any other HTTP status, 2 when the request could not be sent (missing setting, network).
- **Header case.** Header names are sent exactly as written. The production gateway authorizer is case-sensitive about `x-api-key`; libraries that rewrite it to `X-Api-Key` get a 500 `AuthorizerConfigurationException`.

Folders under `requests\`: `00-Auth` 1, `01-Estimate` 60, `02-Shipping` 97, `03-Returns-Management` 17, `05-Tracking` 19, `06-Locator` 4, `07-Documents-Manifest` 15, `08-Product-Matrix-Domestic` 36, `09-Product-Matrix-U-S` 26, `09-Product-Matrix-US-Intl` 22, `10-Product-Matrix-International` 16, `11-Consolidation-Check` 9, `12-Payment-Types` 12, `13-Document-Seeds` 36, `14-Document-Matrix` 50.

## Postman collections

Import `collections\Purolator-REST-Development.postman_collection.json` or `collections\Purolator-REST-Production.postman_collection.json` (Postman: Import > File).

Create one Postman environment per tier with `REST_ClientId`, `REST_Secret`, `REST_XApiKey`, `AccountNumber` and `Different_AccountNumber`. Run *00 · Auth* first; it fills `REST_Token`. Then run each folder top to bottom. For the tracking requests that need your own values, set `trackingId2`, `trackingId3`, `trackReference` and `referenceData` in the environment.

The collections are generated from the scripts, not hand-edited. They carry a collection pre-request script (dates, `RequestReference`), a per-request shipment-date script (with the `shipDaysAhead` override), and a PIN-capture test script. They do not include the label-rendering (Visualize) scripts or the response assertions of the Purolator_2026 collections; use those if you want them.

Thirteen requests are marked *Spec-aligned, not yet verified live* in their description (they were changed to match Purolator's published OpenAPI spec on 2026-10-24 and have not been sent since). The rest were verified live in Certification.

## Tests

```bat
:: PHP: 420 scripts, variable and PIN-chain checks, collection consistency,
:: and an end-to-end run of every folder against a local mock API
php tests\run.php

:: Node 18+: runs the JavaScript inside the Postman collections
node tests\collection_scripts.js
```

Neither test talks to Purolator. HTTP goes to `tests\mock_server.php`, which enforces the gateway rules (token call, bearer token, lowercase `x-api-key`, valid JSON body, no unresolved `{{placeholder}}`). The tests use fake credentials, their own state file, and ignore `.env`.

## Regenerate

```bat
:: 1. Scripts: rebuild requests\ from the Purolator_2026 YAML collection
::    (default source: ..\Purolator_2026\Purolator REST — Development)
php tools\convert_yaml.php

:: 2. Collections: rebuild collections\ from the scripts
php tools\build_collection.php

:: 3. Check the result
php tests\run.php
node tests\collection_scripts.js
```

`convert_yaml.php` deletes and rewrites everything under `requests\`. Use a current checkout of Purolator_2026 (`git pull` first), or the scripts will not match the published requests.

## Layout of this set

```
src\Client.php             cURL client: .env, token, {{variables}}, PIN capture
requests\<folder>\*.php    one runnable script per request (returns its definition when included)
bin\run-folder.php         run a folder in order
tools\convert_yaml.php     Purolator_2026 YAML -> requests\
tools\MiniYaml.php         small YAML reader used by the converter
tools\build_collection.php requests\ -> collections\
collections\               generated Postman collections (Dev, Prod)
tests\                     run.php, collection_scripts.js, mock_server.php
```

## Rules for this repository

- Never commit `.env`, `.state.json` or `output\` (all git-ignored), or any Postman environment.
- Only requests from Purolator_2026 belong here; its README says which ones are verified live.
