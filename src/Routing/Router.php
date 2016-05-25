<?php

namespace Phancy\Routing;

use FastRoute\DataGenerator;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;

class Router
{
  private $collection;

  public function __construct()
  {
    $generator = new DataGenerator\GroupCountBased();
    $parser    = new RouteParser\Std();
    $this->collection = new RouteCollector($parser, $generator);
  }

  public function addRoute($verb, $endpoint, $handler)
  {
    $this->collection->addRoute($verb, $endpoint, $handler);
  }
}
