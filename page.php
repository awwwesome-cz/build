<?php

declare(strict_types=1);

/** @var bool $wantsHtml */
/** @var string $lang */

if (!$wantsHtml) {
    header('Content-Type: text/plain; charset=utf-8');
    echo __('heading') . " (https://build.awwwesome.cz)\n";
    echo "Repository: https://github.com/awwwesome-cz/build\n\n";
    echo __('quick_start_heading') . ":\n";
    echo "  curl -sL https://build.awwwesome.cz/laravel/my-app | bash\n";
    echo "  curl -sL \"https://build.awwwesome.cz/laravel/my-app?with=pgsql,redis\" | bash\n";
    return;
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars(__('title'), ENT_QUOTES, 'UTF-8') ?></title>
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
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }
        .lang-switch {
            display: flex;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        .lang-switch a {
            padding: 0.2rem 0.55rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--surface);
        }
        h1 { margin: 0; font-size: 1.85rem; }
        .lead { color: var(--muted); margin: 0.35rem 0 2rem; }
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
    <div class="topbar">
        <h1><?= htmlspecialchars(__('heading'), ENT_QUOTES, 'UTF-8') ?></h1>
        <nav class="lang-switch" aria-label="Language">
            <a href="?lang=en">EN</a>
            <a href="?lang=cs">CS</a>
        </nav>
    </div>
    <p class="lead">
        <?= htmlspecialchars(__('lead_prefix'), ENT_QUOTES, 'UTF-8') ?>
        <a href="https://github.com/awwwesome-cz/build" target="_blank" rel="noopener">github.com/awwwesome-cz/build</a>
    </p>

    <h2><?= htmlspecialchars(__('quick_start_heading'), ENT_QUOTES, 'UTF-8') ?></h2>
    <p><?= htmlspecialchars(__('quick_start_text'), ENT_QUOTES, 'UTF-8') ?></p>
    <pre><code>curl -sL https://build.awwwesome.cz/laravel/my-app | bash</code></pre>
    <p><?= htmlspecialchars(__('default_stack_text', ['services' => 'pgsql, redis, mailpit']), ENT_QUOTES, 'UTF-8') ?></p>

    <h2><?= htmlspecialchars(__('params_heading'), ENT_QUOTES, 'UTF-8') ?></h2>
    <table>
        <thead>
        <tr>
            <th><?= htmlspecialchars(__('col_param'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars(__('col_meaning'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars(__('col_default'), ENT_QUOTES, 'UTF-8') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><code>/laravel/&lt;app&gt;</code></td>
            <td><?= htmlspecialchars(__('param_path_desc'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars(__('param_required'), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td><code>?with=...</code></td>
            <td><?= htmlspecialchars(__('param_with_desc'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><code>pgsql,redis,mailpit</code></td>
        </tr>
        </tbody>
    </table>

    <h2><?= htmlspecialchars(__('examples_heading'), ENT_QUOTES, 'UTF-8') ?></h2>
    <pre><code># <?= htmlspecialchars(__('example_only_db'), ENT_QUOTES, 'UTF-8') ?>
curl -sL "https://build.awwwesome.cz/laravel/my-app?with=pgsql,redis" | bash

# <?= htmlspecialchars(__('example_add_service'), ENT_QUOTES, 'UTF-8') ?>
curl -sL "https://build.awwwesome.cz/laravel/my-app?with=pgsql,redis,mailpit,meilisearch" | bash

# <?= htmlspecialchars(__('example_none'), ENT_QUOTES, 'UTF-8') ?>
curl -sL "https://build.awwwesome.cz/laravel/my-app?with=none" | bash</code></pre>

    <div class="note">
        <?= htmlspecialchars(__('note_quotes'), ENT_QUOTES, 'UTF-8') ?>
    </div>
</main>
</body>
</html>
