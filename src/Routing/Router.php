<?php

namespace Phancy\Routing;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;

class Router
{
    private $routes;
    private $verbs = ['DELETE', 'GET', 'PATCH', 'POST', 'PUT'];

    public function __construct()
    {
        $generator = new GroupCountBased();
        $parser = new RouteParser\Std();
        $this->routes = new RouteCollector($parser, $generator);
    }

    public function addRoute($verb, $endpoint, $handler)
    {
        $this->routes->addRoute($verb, $endpoint, $handler);
    }

    public function getData()
    {
        return $this->routes->getData();
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
