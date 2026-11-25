<?php

declare(strict_types=1);

return [
    'base_dir'       => __DIR__ . '/../../projects/laravel',
    'script'         => __DIR__ . '/build.sh',
    'composer_image' => 'laravelsail/php85-composer:latest',
    'services'       => 'pgsql,redis,mailpit',
    'pull_services'  => 'pgsql redis mailpit',
];
