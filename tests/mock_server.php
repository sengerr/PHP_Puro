<?php
/**
 * Stand-in for the Purolator REST API, used only by tests/run.php
 * (php -S 127.0.0.1:<port> tests/mock_server.php).
 *
 * It enforces what the real gateway enforces: Basic auth on the token call,
 * a bearer token and a lowercase "x-api-key" header on everything else, and a
 * body that is valid JSON. Paths starting with /fail answer 500.
 */
declare(strict_types=1);

$path = (string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
header('Content-Type: application/json');

/** @param array<string,mixed> $data */
function reply(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data);
}

if (str_starts_with($path, '/fail')) {
    reply(500, ['error' => 'forced failure']);
    return;
}

// Header names exactly as the client wrote them (the production authorizer is case-sensitive).
$headers = getallheaders();

if ($path === '/auth/v1/token') {
    $expected = 'Basic ' . base64_encode('fake-id:fake-secret');
    if (($headers['Authorization'] ?? '') !== $expected) {
        reply(401, ['error' => 'bad client credentials']);
        return;
    }
    $in = json_decode((string)file_get_contents('php://input'), true);
    if (!is_array($in) || ($in['grant_type'] ?? '') !== 'client_credentials' || ($in['scope'] ?? '') !== 'portal_api_sandbox') {
        reply(400, ['error' => 'bad token request', 'got' => $in]);
        return;
    }
    reply(200, ['access_token' => 'mock-token', 'expires_in' => 3600, 'token_type' => 'Bearer']);
    return;
}

if (!array_key_exists('x-api-key', $headers) || $headers['x-api-key'] !== 'fake-key') {
    reply(500, ['message' => 'AuthorizerConfigurationException']);   // what the real gateway does
    return;
}
if (($headers['Authorization'] ?? '') !== 'Bearer mock-token') {
    reply(401, ['error' => 'missing or wrong bearer token']);
    return;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $raw = (string)file_get_contents('php://input');
    $body = json_decode($raw, true);
    if (!is_array($body)) {
        reply(400, ['error' => 'request body is not valid JSON']);
        return;
    }
    if (str_contains($raw, '{{')) {
        reply(400, ['error' => 'unresolved {{placeholder}} in the body']);
        return;
    }
}

if ($path === '/ship/v1/shipment') {
    reply(200, ['validShipment' => true, 'shipmentDetails' => ['shipmentPIN' => '329000000001', 'packagePINs' => ['329000000002']]]);
    return;
}
reply(200, ['ok' => true, 'path' => $path]);
