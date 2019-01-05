<?php

namespace Framework\Router;

/**
 * Class Route
 * Represent a matched route
 */
class Route
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $name, callable $callback, array $params)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
