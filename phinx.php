<?php

require 'public/index.php';

$migrations = [];
$seeds = [];

foreach ($modules as $module) {
    if ($module::MIGRATIONS) {
        $migrations[] = $module::MIGRATIONS;
    }
    if ($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}

return [
    'paths' => [
        'migrations' => $migrations,
        'seeds' => $seeds
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
