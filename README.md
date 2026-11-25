# Project Builder (`build.example.com`)

Jednoduchý webový spouštěč pro generování projektů přes URL (např. `https://build.example.com/laravel/app-example`). Každý stack má vlastní konfiguraci i build skript ve složce `stacks/<stack>/`.

## Struktura projektu

```text
/
├── .htaccess
├── index.php
├── runner.php
├── README.md
└── stacks/
    └── laravel/
        ├── config.php
        └── build.sh
