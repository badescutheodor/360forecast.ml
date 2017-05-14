<?php

return [
    'paths' => [
        'migrations' => '../../../../database/migrations'
    ],
    'migration_base_class' => '\App\Migration\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'dev' => [
            'adapter'   => 'mysql',
            'host'      => getenv('DATABASE_HOST') ? getenv('DATABASE_HOST') : '127.0.0.1',
            'port'      => getenv('DATABASE_PORT') ? getenv('DATABASE_PORT') : 3306,
            'name'      => getenv('DATABASE_NAME') ? getenv('DATABASE_NAME') : '360forecast',
            'user'      => getenv('DATABASE_USER') ? getenv('DATABASE_USER') : 'root',
            'pass'      => getenv('DATABASE_PASSWORD') ? getenv('DATABASE_PASSWORD') : 'ERNnfFh9Ax2PUn9NLnFW',
            'charset'   => 'utf8'
        ]
    ]
];