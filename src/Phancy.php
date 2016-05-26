<?php
namespace Phancy;

use Phancy\Interfaces\Serializer;
use Phancy\Routing\Router;

class Phancy
{
    private $router;
    private $resources;
    private $request;
    private $response;
    private $verbs = ['DELETE', 'GET', 'PATCH', 'POST', 'PUT'];
    private $beforeFilters;
    private $afterFilters;
    private $serializer;

    public function __construct()
    {
        $this->resources = [];
        $this->beforeFilters = [];
        $this->afterFilters = [];
        $this->errorHandler = null;
        $this->request = new Http\Request;
        $this->response = new Http\Response;
        $this->router = new Routing\Router;
        $this->serializer = new Serializers\JsonSerializer();
    }

    public function addResource(Interfaces\Resource $resource)
    {
        array_push($this->resources, $resource);
    }

    public function process(Serializer $serializer = null)
    {
        foreach ($this->resources as $resource) {
            $resource->endpoints($this);
        }

        $dispatcher = new Routing\Dispatcher($this->router);
        $data = $dispatcher->dispatch($this->request->getMethod(), $this->request->getUri());

        if ($serializer !== null) {
            $this->serializer = $serializer;
        }

        $this->callBeforeFilters();

        $this->response->send($serializer);

        $this->callAfterFilters();
    }

    public function addBeforeFilter(callable $callback)
    {
        array_push($this->beforeFilters, $callback);
    }

    public function addAfterFilter(callable $callback)
    {
        array_push($this->afterFilters, $callback);
    }

    public function __call($verb, $params)
    {
        if (in_array($verb, $this->verbs)) {
            // $params[0] is the route's endpoint
            // $params[1] is the route's handler
            $this->router->addRoute($verb, $params[0], $params[1]);
        } else {
            throw new \Exception('Method not found');
        }
    }

    private function callBeforeFilters()
    {
        foreach ($this->beforeFilters as $beforeFilter) {
            $response = call_user_func_array($beforeFilter, [$this->request, $this->response]);

            if ($response !== null) {
                $response->send($this->serializer);
            }
        }
    }

    private function callAfterFilters()
    {
        foreach ($this->afterFilters as $afterFilter) {
            call_user_func_array($afterFilter, [$this->request, $this->response]);
        }
    }
}
