<?php

return [
    'redis' => [
        'scheme'     => 'tcp',
        'password'   => getenv('REDIS_PASSWORD') ? getenv('REDIS_PASSWORD') : '5bvV85Paa9PFvjEY',
        'host'       => getenv('REDIS_HOST') ? getenv('REDIS_HOST') : '127.0.0.1',
        'port'       => getenv('REDIS_PORT') ? getenv('REDIS_PORT') : 6379,
        'persistent' => '1',
    ]
];