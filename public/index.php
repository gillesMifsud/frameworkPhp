<?php
require '../vendor/autoload.php';

// Conteneur d'injection de dépendances PHP DI
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');
// Override /config/config.php
$builder->addDefinitions(dirname(__DIR__) . '/config.php');
$container = $builder->build();

$app = new \Framework\App($container, [
    \App\Blog\BlogModule::class
]);

$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);
