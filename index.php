<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');
header('X-Accel-Buffering: no');

@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', '0');
@ini_set('max_execution_time', '0');

while (ob_get_level() > 0) {
    ob_end_flush();
}
ob_implicit_flush(true);

$rawStack = $_GET['stack'] ?? '';
$rawApp = $_GET['app'] ?? '';

$allowedStacks = ['laravel', 'angular'];

if (!in_array($rawStack, $allowedStacks, true)) {
    http_response_code(400);
    echo "Nepodporovaný stack '{$rawStack}'. Povolené stacky: " . implode(', ', $allowedStacks) . "\n";
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $rawApp)) {
    http_response_code(400);
    echo "Neplatný název aplikace. Povoleny jsou pouze znaky [a-zA-Z0-9_-].\n";
    exit;
}

$stack = $rawStack;
$appName = $rawApp;

$stackBaseDir = __DIR__ . '/projects/' . $stack;
$appDir = $stackBaseDir . '/' . $appName;

if (!is_dir($stackBaseDir) && !mkdir($stackBaseDir, 0775, true) && !is_dir($stackBaseDir)) {
    http_response_code(500);
    echo "Nepodařilo se vytvořit adresář stacku: {$stackBaseDir}\n";
    exit;
}

if (file_exists($appDir)) {
    http_response_code(409);
    echo "Složka '{$appName}' již existuje v {$stackBaseDir}.\n";
    exit;
}

if (!mkdir($appDir, 0775, true) && !is_dir($appDir)) {
    http_response_code(500);
    echo "Nepodařilo se vytvořit složku projektu: {$appDir}\n";
    exit;
}

if ($stack === 'laravel') {
    $services = 'pgsql,redis,mailpit';
    $pullServices = 'pgsql redis mailpit';

    $innerCmd =
        'laravel new ' . escapeshellarg($appName) . ' --no-interaction && ' .
        'cd ' . escapeshellarg($appName) . ' && ' .
        '(composer show laravel/sail 2>/dev/null || composer require laravel/sail --dev) && ' .
        'php ./artisan sail:install --with=' . $services;

    $command = sprintf(
        'bash -c %s',
        escapeshellarg(
            'set -e && ' .
            'docker info > /dev/null 2>&1 || { echo "Docker is not running."; exit 1; } && ' .
            'docker run --rm --pull=always ' .
            '-v ' . escapeshellarg($stackBaseDir) . ':/opt ' .
            '-w /opt ' .
            'laravelsail/php85-composer:latest ' .
            'bash -c ' . escapeshellarg($innerCmd) . ' && ' .
            'cd ' . escapeshellarg($appDir) . ' && ' .
            './vendor/bin/sail pull ' . $pullServices . ' && ' .
            './vendor/bin/sail build && ' .
            'echo "" && echo "Hotovo! Laravel projekt je připraven v: ' . $appDir . '"'
        )
    );
} elseif ($stack === 'angular') {
    // Generování nového Angular projektu přes Node kontejner (bez nutnosti mít globálně nainstalované Node/Angular CLI na hostiteli)
    $innerCmd = 'npx --yes @angular/cli@latest new ' . escapeshellarg($appName) . ' --standalone --style=scss --routing --skip-git --interactive=false';

    $command = sprintf(
        'bash -c %s',
        escapeshellarg(
            'set -e && ' .
            'docker info > /dev/null 2>&1 || { echo "Docker is not running."; exit 1; } && ' .
            'docker run --rm ' .
            '-v ' . escapeshellarg($stackBaseDir) . ':/opt ' .
            '-w /opt ' .
            'node:22-alpine ' .
            'sh -c ' . escapeshellarg($innerCmd) . ' && ' .
            'echo "" && echo "Hotovo! Angular projekt je připraven v: ' . $appDir . '"'
        )
    );
}

echo "Spouštím generování:\n";
echo "- Stack:   {$stack}\n";
echo "- Projekt: {$appName}\n";
echo "- Cílová složka: {$appDir}\n\n";

$descriptors = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open($command, $descriptors, $pipes, $stackBaseDir);

if (!is_resource($process)) {
    http_response_code(500);
    echo "Nepodařilo se spustit proces.\n";
    exit;
}

fclose($pipes[0]);

stream_set_blocking($pipes[1], false);
stream_set_blocking($pipes[2], false);

while (true) {
    $read = [];
    if (!feof($pipes[1])) {
        $read[] = $pipes[1];
    }
    if (!feof($pipes[2])) {
        $read[] = $pipes[2];
    }

    if (empty($read)) {
        break;
    }

    $write = null;
    $except = null;
    if (stream_select($read, $write, $except, 1) > 0) {
        foreach ($read as $stream) {
            $chunk = fread($stream, 4096);
            if ($chunk !== false && $chunk !== '') {
                echo $chunk;
                flush();
            }
        }
    }
}

fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

echo "\nUkončeno s návratovým kódem: {$exitCode}\n";
