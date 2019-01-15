<?php

use App\Framework\Renderer\TwigRendererFactory;
use Framework\Renderer\RendererInterface;
use Framework\Router;

return [
    'database.host' => 'localhost',
    'database.user' => 'root',
    'database.pass' => 'root',
    'database.name' => 'frameworkphp',
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(Router\RouterTwigExtension::class)
    ],
    Router::class => \DI\create(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class)
];
