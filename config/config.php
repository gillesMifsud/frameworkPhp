<?php

use App\Framework\Renderer\TwigRendererFactory;
use Framework\Renderer\RendererInterface;
use Framework\Router;

return [
    'views.path' => dirname(__DIR__) . '/views',
    Router::class => \DI\create(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class)
];
