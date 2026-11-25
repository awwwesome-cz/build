<?php

declare(strict_types=1);

$supportedLangs = ['cs', 'en'];
$defaultLang = 'en';

$lang = $defaultLang;
if (isset($_GET['lang']) && is_string($_GET['lang'])) {
    $qLang = strtolower(trim($_GET['lang']));
    if (in_array($qLang, $supportedLangs, true)) {
        $lang = $qLang;
    }
} else {
    $acceptLang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '');
    if (str_starts_with($acceptLang, 'cs')) {
        $lang = 'cs';
    }
}

$translationsFile = __DIR__ . '/lang/' . $lang . '.php';
$translations = is_file($translationsFile) ? require $translationsFile : require __DIR__ . '/lang/en.php';

/**
 * Překladová funkce ve stylu Laravelu __().
 *
 * @param array<string, string> $replace
 */
function __(string $key, array $replace = []): string
{
    global $translations;

    $text = $translations[$key] ?? $key;

    foreach ($replace as $k => $v) {
        $text = str_replace(':' . $k, $v, $text);
    }

    return $text;
}

$rawStack = $_GET['stack'] ?? '';
$rawApp = $_GET['app'] ?? '';

if ($rawStack === '' && $rawApp === '') {
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $isCurl = stripos($userAgent, 'curl') !== false;
    $wantsHtml = !$isCurl && stripos($accept, 'text/html') !== false;

    require __DIR__ . '/page.php';
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

$allowedStacks = ['laravel'];

if (!in_array($rawStack, $allowedStacks, true)) {
    http_response_code(400);
    echo __('err_invalid_stack') . "\nexit 1\n";
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $rawApp)) {
    http_response_code(400);
    echo __('err_invalid_app') . "\nexit 1\n";
    exit;
}

$configPath = __DIR__ . '/stacks/' . $rawStack . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    echo __('err_missing_config') . "\nexit 1\n";
    exit;
}

$config = require $configPath;

if (!is_file($config['template'])) {
    http_response_code(500);
    echo __('err_missing_template') . "\nexit 1\n";
    exit;
}

$script = file_get_contents($config['template']);
if ($script === false) {
    http_response_code(500);
    echo __('err_read_template') . "\nexit 1\n";
    exit;
}

$services = $config['services'];
if (isset($_GET['with']) && is_string($_GET['with'])) {
    $candidate = trim($_GET['with']);
    if ($candidate === 'none' || preg_match('/^[a-z0-9]+(?:,[a-z0-9]+)*$/', $candidate)) {
        $services = $candidate;
    } else {
        http_response_code(400);
        echo __('err_invalid_with') . "\nexit 1\n";
        exit;
    }
}

$pullServices = ($services === 'none')
    ? 'none'
    : str_replace(',', ' ', $services);

$output = strtr($script, [
    '__APP_NAME__' => $rawApp,
    '__SERVICES__' => $services,
    '__PULL_SERVICES__' => $pullServices,
    '__COMPOSER_IMAGE__' => $config['composer_image'],
]);

echo $output;
