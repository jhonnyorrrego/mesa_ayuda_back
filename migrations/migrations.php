<?php

return [
    'name' => 'SAIA Migrations',
    'migrations_namespace' => 'SAIA\Migrations\Base',
    'table_name' => 'migrations_base',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => 'list',
    'all_or_nothing' => true,
    'check_database_platform' => true,
];
