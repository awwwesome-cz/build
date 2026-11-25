<?php

declare(strict_types=1);

$rawStack = $_GET['stack'] ?? '';
$rawApp = $_GET['app'] ?? '';

if ($rawStack === '' && $rawApp === '') {
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $isCurl = stripos($userAgent, 'curl') !== false;
    $wantsHtml = !$isCurl && stripos($accept, 'text/html') !== false;

    if ($wantsHtml) {
        header('Content-Type: text/html; charset=utf-8');
        ?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Project Builder — build.awwwesome.cz</title>
  <style>
    :root {
      color-scheme: dark;
      --bg: #0d1117;
      --surface: #161b22;
      --border: #30363d;
      --text: #e6edf3;
      --muted: #8b949e;
      --accent: #58a6ff;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      padding: 2.5rem 1.25rem;
    }
    main {
      max-width: 780px;
      margin: 0 auto;
    }
    h1 { margin: 0 0 0.5rem; font-size: 1.85rem; }
    .lead { color: var(--muted); margin: 0 0 2rem; }
    h2 { font-size: 1.2rem; margin: 2rem 0 0.75rem; border-bottom: 1px solid var(--border); padding-bottom: 0.35rem; }
    pre {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 0.9rem 1rem;
      overflow-x: auto;
      margin: 0.5rem 0 1rem;
    }
    code {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: 0.9rem;
    }
    :not(pre) > code {
      background: var(--surface);
      border: 1px solid var(--border);
      padding: 0.15rem 0.4rem;
      border-radius: 5px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0.75rem 0 1.25rem;
      background: var(--surface);
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      text-align: left;
      padding: 0.65rem 0.85rem;
      border-bottom: 1px solid var(--border);
      font-size: 0.92rem;
    }
    th { color: var(--muted); font-weight: 600; }
    a { color: var(--accent); text-decoration: none; }
    a:hover { text-decoration: underline; }
    .note {
      background: var(--surface);
      border-left: 3px solid var(--accent);
      padding: 0.75rem 1rem;
      border-radius: 0 8px 8px 0;
      color: var(--muted);
      font-size: 0.92rem;
    }
  </style>
</head>
<body>
  <main>
    <h1>Project Builder</h1>
    <p class="lead">
      Generátor instalačních skriptů pro spuštění přes terminál. Repozitář:
      <a href="https://github.com/awwwesome-cz/build" target="_blank" rel="noopener">github.com/awwwesome-cz/build</a>
    </p>

    <h2>Rychlé spuštění</h2>
    <p>V terminálu přejdi do cílové složky a spusť:</p>
    <pre><code>curl -sL https://build.awwwesome.cz/laravel/moj-projekt | bash</code></pre>
    <p>Výchozí stack vytváří nový Laravel projekt s <strong>PHP 8.5</strong> a službami <code>pgsql</code>, <code>redis</code> a <code>mailpit</code>.</p>

    <h2>Parametry v URL</h2>
    <table>
      <thead>
        <tr>
          <th>Část / parametr</th>
          <th>Význam</th>
          <th>Výchozí</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><code>/laravel/&lt;nazev&gt;</code></td>
          <td>Název projektu (<code>[a-zA-Z0-9_-]{1,64}</code>)</td>
          <td>povinné</td>
        </tr>
        <tr>
          <td><code>?with=...</code></td>
          <td>Služby pro Laravel Sail oddělené čárkou, nebo <code>none</code></td>
          <td><code>pgsql,redis,mailpit</code></td>
        </tr>
      </tbody>
    </table>

    <h2>Příklady</h2>
    <pre><code># Pouze PostgreSQL a Redis
curl -sL "https://build.awwwesome.cz/laravel/moj-projekt?with=pgsql,redis" | bash

# Přidání další služby (např. Meilisearch)
curl -sL "https://build.awwwesome.cz/laravel/moj-projekt?with=pgsql,redis,mailpit,meilisearch" | bash

# Bez dalších služeb
curl -sL "https://build.awwwesome.cz/laravel/moj-projekt?with=none" | bash</code></pre>

    <div class="note">
      Při použití query parametrů (<code>?...</code>) dej celou URL do uvozovek, aby ji shell správně předal příkazu <code>curl</code>.
    </div>
  </main>
</body>
</html>
<?php
        exit;
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo "Project Builder (https://build.awwwesome.cz)\n";
    echo "Repository: https://github.com/awwwesome-cz/build\n\n";
    echo "Usage:\n";
    echo "  curl -sL https://build.awwwesome.cz/laravel/moj-projekt | bash\n";
    echo "  curl -sL \"https://build.awwwesome.cz/laravel/moj-projekt?with=pgsql,redis\" | bash\n";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

$allowedStacks = ['laravel'];

if (!in_array($rawStack, $allowedStacks, true)) {
    http_response_code(400);
    echo "# Chyba: neplatny stack\nexit 1\n";
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $rawApp)) {
    http_response_code(400);
    echo "# Chyba: neplatny nazev aplikace\nexit 1\n";
    exit;
}

$configPath = __DIR__ . '/stacks/' . $rawStack . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    echo "# Chyba: chybi konfigurace stacku\nexit 1\n";
    exit;
}

$config = require $configPath;

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

$services = $config['services'];
if (isset($_GET['with']) && is_string($_GET['with'])) {
    $candidate = trim($_GET['with']);
    if ($candidate === 'none' || preg_match('/^[a-z0-9]+(?:,[a-z0-9]+)*$/', $candidate)) {
        $services = $candidate;
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
