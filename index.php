<?php

declare(strict_types=1);

require __DIR__ . '/runner.php';

header('Content-Type: text/plain; charset=utf-8');
header('X-Accel-Buffering: no');

@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', '0');
@ini_set('max_execution_time', '0');

while (ob_get_level() > 0) {
    ob_end_flush();
}
ob_implicit_flush(true);

$allowedStacks = ['laravel'];
$rawStack = $_GET['stack'] ?? '';
$rawApp = $_GET['app'] ?? '';

if (!in_array($rawStack, $allowedStacks, true)) {
    http_response_code(400);
    echo "Neplatný nebo nepodporovaný stack.\n";
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $rawApp)) {
    http_response_code(400);
    echo "Neplatný název aplikace. Povoleny jsou pouze znaky [a-zA-Z0-9_-].\n";
    exit;
}

$stackConfigPath = __DIR__ . '/stacks/' . $rawStack . '/config.php';

if (!is_file($stackConfigPath)) {
    http_response_code(500);
    echo "Chybí konfigurace pro stack: {$rawStack}\n";
    exit;
}

$config = require $stackConfigPath;

$appName = $rawApp;
$baseDir = $config['base_dir'];
$appDir = $baseDir . '/' . $appName;

if (!is_dir($baseDir) && !mkdir($baseDir, 0775, true) && !is_dir($baseDir)) {
    http_response_code(500);
    echo "Nepodařilo se vytvořit cílový adresář: {$baseDir}\n";
    exit;
}

if (file_exists($appDir)) {
    http_response_code(409);
    echo "Složka '{$appName}' již existuje v {$baseDir}.\n";
    exit;
}

if (!mkdir($appDir, 0775, true) && !is_dir($appDir)) {
    http_response_code(500);
    echo "Nepodařilo se vytvořit složku projektu: {$appDir}\n";
    exit;
}

$command = sprintf(
    '%s %s %s %s %s %s',
    escapeshellarg($config['script']),
    escapeshellarg($baseDir),
    escapeshellarg($appName),
    escapeshellarg($config['composer_image']),
    escapeshellarg($config['services']),
    escapeshellarg($config['pull_services'])
);

echo "Spouštím stack '{$rawStack}' pro aplikaci '{$appName}'\n\n";

$exitCode = run_build_script($command, $baseDir);

echo "\nUkončeno s návratovým kódem: {$exitCode}\n";
