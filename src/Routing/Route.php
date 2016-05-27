<?php

namespace Phancy\Routing;

class Route
{
    private $callback;
    private $parameters;

    public function __construct(callable $callback, array $parameters)
    {
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}