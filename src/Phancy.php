<?php
namespace Phancy;

use Phancy\Interfaces\Serializer;
use Phancy\Routing\Router;

class Phancy {
    private $router;
    private $resources;
    private $request;
    private $response;
    private $verbs = ['DELETE', 'GET', 'PATCH', 'POST', 'PUT'];

    public function __construct()
    {
        $this->resources = [];
        $this->request = new Http\Request;
        $this->response = new Http\Response;
        $this->router = new Routing\Router;
    }

    public function register(Interfaces\Resource $resource)
    {
        array_push($this->resources, $resource);
    }

    public function process(Serializer $serializer = null)
    {
        foreach ($this->resources as $resource) {
            $resource->endpoints($this);
        }

        // dispatch routes
        $dispatcher = new Routing\Dispatcher($this->router);
        $data = $dispatcher->dispatch();

        if ($serializer === null) {
            $serializer = new Serializers\JsonSerializer($data);
        }

        $this->response->send($serializer);
    }

    public function __call($verb, $params)
    {
      if (in_array($verb, $this->verbs)) {
          // $params[0] is the route's endpoint
          // $params[1] is the route's handler
          $this->router->addRoute($verb, $params[0], $params[1]);
      } else {
          thrown new \Exception('Method not found');
      }
    }
}
