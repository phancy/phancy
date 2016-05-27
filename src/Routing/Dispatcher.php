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

    public function dispatch(Request $request, Response $response)
    {
        $route = $this->dispatcher->dispatch($request->getMethod(), $request->getRequestUri());

        switch($route[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new HttpNotFound();
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpMethodNotAllowed();
            case \FastRoute\Dispatcher::FOUND:
                return $response->setData(call_user_func_array($route[1], $route[2]));
                break;
        }
    }
}
