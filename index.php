<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$allowedStacks = ['laravel'];
$rawStack =$_GET['stack'] ?? '';
$rawApp =$_GET['app'] ?? '';

if (!in_array($rawStack,$allowedStacks, true)) {
    http_response_code(400);
    echo "# Chyba: neplatny stack\nexit 1\n";
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_-]{1,64}$/',$rawApp)) {
    http_response_code(400);
    echo "# Chyba: neplatny nazev aplikace\nexit 1\n";
    exit;
}

$configPath = __DIR__ . '/stacks/' .$rawStack . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    echo "# Chyba: chybi konfigurace stacku\nexit 1\n";
    exit;
}

$config = require$configPath;

if (!is_file($config['template'])) {
    http_response_code(500);
    echo "# Chyba: chybi sablona skriptu\nexit 1\n";
    exit;
}

$script = file_get_contents($config['template']);
if ($script === false) {
    http_response_code(500);
    echo "# Chyba: nelze nacist sablonu\nexit 1\n";
    exit;
}

// Volitelný query parametr ?with=... (např. ?with=pgsql,redis nebo ?with=none)
$services =$config['services'];
if (isset($_GET['with']) && is_string($_GET['with'])) {
    $candidate = trim($_GET['with']);
    if ($candidate === 'none' || preg_match('/^[a-z0-9]+(?:,[a-z0-9]+)*$/',$candidate)) {
        $services =$candidate;
    } else {
        http_response_code(400);
        echo "# Chyba: neplatny parametr with\nexit 1\n";
        exit;
    }
}

$pullServices = ($services === 'none')
    ? 'none'
    : str_replace(',', ' ', $services);

$output = strtr($script, [
    '__APP_NAME__'       => $rawApp,
    '__SERVICES__'       => $services,
    '__PULL_SERVICES__'  => $pullServices,
    '__COMPOSER_IMAGE__' => $config['composer_image'],
]);

echo $output;
