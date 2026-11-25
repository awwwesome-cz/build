# Project Builder (`build.awwwesome.cz`)

Generátor instalačních bash skriptů dostupný přes URL (např. `https://build.awwwesome.cz/laravel/app-example`). Požadavek vrátí sestavený skript jako prostý text (`text/plain`), takže ho lze rovnou spustit v terminálu přes `curl -sL <URL> | bash`.

## Struktura projektu

```text
/
├── .htaccess
├── index.php
├── README.md
└── stacks/
    └── laravel/
        ├── config.php
        └── template.sh
