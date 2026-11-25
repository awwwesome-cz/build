<?php

declare(strict_types=1);

return [
    'title'                => 'Project Builder — build.awwwesome.cz',
    'heading'              => 'Project Builder',
    'lead_prefix'          => 'Generátor instalačních skriptů pro spuštění přes terminál. Repozitář:',
    'quick_start_heading'  => 'Rychlé spuštění',
    'quick_start_text'     => 'V terminálu přejdi do cílové složky a spusť:',
    'default_stack_text'   => 'Výchozí stack vytváří nový Laravel projekt s PHP 8.5 a službami :services.',
    'params_heading'       => 'Parametry v URL',
    'col_param'            => 'Část / parametr',
    'col_meaning'          => 'Význam',
    'col_default'          => 'Výchozí',
    'param_path_desc'      => 'Název projektu ([a-zA-Z0-9_-]{1,64})',
    'param_required'       => 'povinné',
    'param_with_desc'      => 'Služby pro Laravel Sail oddělené čárkou, nebo none',
    'examples_heading'     => 'Příklady',
    'example_only_db'      => 'Pouze PostgreSQL a Redis',
    'example_add_service'  => 'Přidání další služby (např. Meilisearch)',
    'example_none'         => 'Bez dalších služeb',
    'note_quotes'          => 'Při použití query parametrů (?...) dej celou URL do uvozovek, aby ji shell správně předal příkazu curl.',
    'err_invalid_stack'    => '# Chyba: neplatny stack',
    'err_invalid_app'      => '# Chyba: neplatny nazev aplikace',
    'err_missing_config'   => '# Chyba: chybi konfigurace stacku',
    'err_missing_template' => '# Chyba: chybi sablona skriptu',
    'err_read_template'    => '# Chyba: nelze nacist sablonu',
    'err_invalid_with'     => '# Chyba: neplatny parametr with',
];
