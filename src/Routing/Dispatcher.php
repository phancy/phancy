<?php

namespace Phancy\Routing;

use FastRoute\Dispatcher\GroupCountBased;

class Dispatcher implements \FastRoute\Dispatcher
{
    private $dispatcher;

    public function __construct(Router $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    public function dispatch($request, $response)
    {
        $route = $this->dispatcher->dispatch($request->getMethod(), $request->getRequestUri());

        switch($route[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // return 404 Not Found
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // return 405 Method Not Allowed
                break;
            case \FastRoute\Dispatcher::FOUND:
                return call_user_func_array($route[1], [$request, $response]);
                break;
        }
    }
}
