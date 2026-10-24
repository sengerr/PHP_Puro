<?php
/**
 * AUTH · OKTA Token
 *
 * POST /auth/v1/token   (folder: 00 · Auth)
 *
 * Fetches a bearer token with HTTP Basic auth (PUROLATOR_CLIENT_ID :
 * PUROLATOR_CLIENT_SECRET) and the body {"grant_type": "client_credentials",
 * "scope": "portal_api_sandbox" | "portal_api"} -- the scope follows
 * PUROLATOR_ENV. Every other example fetches its own token the same way, so you
 * only need this one to check your credentials. The token is printed masked.
 *
 * Run:  php examples/00-auth/auth-okta-token.php
 */

declare(strict_types=1);

require __DIR__ . '/../../src/PurolatorClient.php';

use PhpPuro\PurolatorClient;

$client = new PurolatorClient();
$token = $client->token();
// Never print a whole token: show only enough to tell two tokens apart.
echo 'Token OK for ', $client->tier(), ' (', $client->scope(), '): ', substr($token, 0, 12), '...', PHP_EOL;
