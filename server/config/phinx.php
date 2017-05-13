<?php

return [
    'paths' => [
        'migrations' => '../../database/migrations'
    ],
    'migration_base_class' => '\App\Migration\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'dev' => [
            'adapter'  => 'sqlite',
            'name' => 'C:\Users\theo\Desktop\360forecast.ml\server\storage\database\database.sqlite'
        ]
    ]
];