<?php

return [
    'sqlite' => [
        'driver'   => 'sqlite',
        'database' => getenv('DATABASE_PATH') ? getenv('DATABASE_PATH') : 'storage/database/database.sqlite',
        'prefix'   => '',
    ]
];