<?php

require 'public/index.php';

return [
    'paths' => [
        'migrations' => __DIR__ . '/db',
        'seeds' => __DIR__ . '/db'
    ],
    'environments' => [
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $app->getConatiner()->get('database.host'),
            'name' => $app->getConatiner()->get('database.name'),
            'user' => $app->getConatiner()->get('database.user'),
            'pass' => $app->getConatiner()->get('database.pass')
        ]
    ]
];
