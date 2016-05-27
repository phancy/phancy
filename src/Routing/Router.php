<?php

namespace Phancy\Routing;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;

class Router
{
    private $collection;
    private $verbs = ['DELETE', 'GET', 'PATCH', 'POST', 'PUT'];

    public function __construct()
    {
        $generator = new GroupCountBased();
        $parser = new RouteParser\Std();
        $this->collection = new RouteCollector($parser, $generator);
    }

    public function addRoute($verb, $endpoint, $handler)
    {
        $this->collection->addRoute($verb, $endpoint, $handler);
    }

    public function getData()
    {
        return $this->collection->getData();
    }

    public function __call($name, $arguments)
    {
        $verb = strtoupper($name);
        if (in_array($verb, $this->verbs)) {
            return $this->addRoute($verb, $arguments[0], $arguments[1]);
        }

        throw new \BadMethodCallException();
    }
}
