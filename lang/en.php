<?php

declare(strict_types=1);

return [
    'title'                => 'Project Builder — build.awwwesome.cz',
    'heading'              => 'Project Builder',
    'lead_prefix'          => 'Install script generator for terminal execution. Repository:',
    'quick_start_heading'  => 'Quick start',
    'quick_start_text'     => 'In your terminal, go to the target directory and run:',
    'default_stack_text'   => 'The default stack creates a new Laravel project with PHP 8.5 and services :services.',
    'params_heading'       => 'URL parameters',
    'col_param'            => 'Path / parameter',
    'col_meaning'          => 'Description',
    'col_default'          => 'Default',
    'param_path_desc'      => 'Project name ([a-zA-Z0-9_-]{1,64})',
    'param_required'       => 'required',
    'param_with_desc'      => 'Comma-separated Laravel Sail services, or none',
    'examples_heading'     => 'Examples',
    'example_only_db'      => 'PostgreSQL and Redis only',
    'example_add_service'  => 'Add another service (e.g. Meilisearch)',
    'example_none'         => 'No additional services',
    'note_quotes'          => 'When using query parameters (?...), wrap the full URL in quotes so your shell passes it properly to curl.',
    'err_invalid_stack'    => '# Error: invalid stack',
    'err_invalid_app'      => '# Error: invalid application name',
    'err_missing_config'   => '# Error: missing stack config',
    'err_missing_template' => '# Error: missing script template',
    'err_read_template'    => '# Error: unable to read template',
    'err_invalid_with'     => '# Error: invalid with parameter',
];
