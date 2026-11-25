<?php

declare(strict_types=1);

return [
    'template'       => __DIR__ . '/template.sh',
    'composer_image' => 'laravelsail/php84-composer:latest',
    'services'       => 'pgsql,redis,mailpit',
    'pull_services'  => 'pgsql redis mailpit',
];
