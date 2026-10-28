<?php
/**
 * Run every request script in one folder, in the original Postman order.
 *
 * Usage:  php bin/run-folder.php 02-Shipping [--dry-run] [--save]
 *
 * Shipment PINs captured by one request are kept in .state.json and read by
 * the requests after it, so run a folder top to bottom the first time.
 */
declare(strict_types=1);

require dirname(__DIR__) . '/src/Client.php';

use Puro\Client;

$args = array_slice($argv, 1);
$folder = array_shift($args);
if ($folder === null || str_starts_with($folder, '--')) {
    fwrite(STDERR, "Usage: php bin/run-folder.php <folder> [--dry-run] [--save]\nFolders:\n");
    foreach (glob(dirname(__DIR__) . '/requests/*', GLOB_ONLYDIR) ?: [] as $d) {
        fwrite(STDERR, '  ' . basename($d) . "\n");
    }
    exit(2);
}

$dir = dirname(__DIR__) . '/requests/' . basename($folder);
$files = glob($dir . '/*.php') ?: [];
if (!$files) {
    fwrite(STDERR, "No scripts in {$dir}\n");
    exit(2);
}

// Read every definition, then sort by the original Postman order.
$defs = [];
foreach ($files as $file) {
    $defs[] = require $file;
}
usort($defs, static fn(array $a, array $b): int => [$a['order'] ?? 0, $a['name']] <=> [$b['order'] ?? 0, $b['name']]);

$failed = 0;
foreach ($defs as $i => $def) {
    echo "\n=== " . ($i + 1) . '/' . count($defs) . '  ' . $def['name'] . " ===\n";
    if (Client::run($def, $args) !== 0) {
        $failed++;
    }
}
echo "\nDone: " . (count($defs) - $failed) . ' ok, ' . $failed . " not ok.\n";
exit($failed === 0 ? 0 : 1);
