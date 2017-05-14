<?php

return [
    'sqlite' => [
        'driver'   => 'sqlite',
        'database' => getenv('DATABASE_PATH') ? getenv('DATABASE_PATH') : 'storage/database/database.sqlite',
        'prefix'   => '',
    ],
    'mysql' => [
        'driver'    => 'mysql',
        'host'      => getenv('DATABASE_HOST') ? getenv('DATABASE_HOST') : '127.0.0.1',
        'port'      => getenv('DATABASE_PORT') ? getenv('DATABASE_PORT') : 3306,
        'database'  => getenv('DATABASE_NAME') ? getenv('DATABASE_NAME') : '360forecast.ml',
        'username'  => getenv('DATABASE_USER') ? getenv('DATABASE_USER') : 'root',
        'password'  => getenv('DATABASE_PASSWORD') ? getenv('DATABASE_PASSWORD') : 'ERNnfFh9Ax2PUn9NLnFW',
        'collation' => 'utf8_unicode_ci',
        'charset'   => 'utf8'
    ]
];