<?php

namespace Phancy\Routing;

use Phancy\Exceptions\HttpMethodNotAllowed;
use Phancy\Exceptions\HttpNotFound;
use Phancy\Http\Request;
use Phancy\Http\Response;
use FastRoute\Dispatcher\GroupCountBased;

class Dispatcher
{
    private $dispatcher;

    public function __construct(Router $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    public function dispatch($method, $uri)
    {
        $route = $this->dispatcher->dispatch($method, $uri);

        switch($route[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new HttpNotFound();
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpMethodNotAllowed();
            case \FastRoute\Dispatcher::FOUND:
                return new Route($route[1], $route[2]);
        }
    }
}
