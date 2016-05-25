<?php

namespace Phancy\Routing;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\Dispatcher as FastRouteDispatcher;

class Dispatcher implements FastRouteDispatcher
{
  private $dispatcher;

  public function __construct(RouteCollector $routes)
  {
    $this->dispatcher = new GroupCountBased($routes->getData());
  }

  public function dispatch($method, $uri)
  {
    $route = $this->dispatcher->dispatch($method, $uri);

    switch($route[0]) {
      case FastRouteDispatcher::NOT_FOUND:
        // return 404 Not Found
        break;
      case FastRouteDispatcher::METHOD_NOT_ALLOWED:
        // return 405 Method Not Allowed
        break;
      case FastRouteDispatcher::FOUND:
        return var_dump($route);
        break;
    }
  }
}
