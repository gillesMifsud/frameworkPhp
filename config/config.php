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
        \DI\get(Router\RouterTwigExtension::class),
        \DI\get(\Framework\Twig\PagerFantaExtension::class),
        \DI\get(\Framework\Twig\TextExtension::class),
        \DI\get(\Framework\Twig\TimeExtension::class)
    ],
    Router::class => \DI\create(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    PDO::class => function (Psr\Container\ContainerInterface $c) {
        return new PDO(
            'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
            $c->get('database.user'),
            $c->get('database.pass'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
];
