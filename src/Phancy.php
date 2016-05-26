<?php
namespace Phancy;

use Phancy\Http\Response;
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
        $this->request = new Http\Request();
        $this->response = new Http\Response();
        $this->router = new Routing\Router();
        $this->serializer = new Serializers\JsonSerializer();
    }

    public function addResource(Interfaces\Resource $resource)
    {
        array_push($this->resources, $resource);
    }

    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function process()
    {
        foreach ($this->resources as $resource) {
            $resource->endpoints($this);
        }

        $dispatcher = new Routing\Dispatcher($this->router);
        $response = $dispatcher->dispatch($this->request->getMethod(), $this->request->getUri());

        $this->callBeforeFilters();
        $this->sendResponse($response);
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

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->verbs)) {
            $this->router->addRoute($name, $arguments[0], $arguments[1]);
        } else {
            throw new \Exception('Method not found');
        }
    }

    private function sendResponse(Response $response)
    {
        $sendable_response = new \Symfony\Component\HttpFoundation\Response(
            $this->serializer->serialize($response->getData()),
            $response->getStatusCode(),
            $response->getHeaders()
        );
        $sendable_response->send();
    }

    private function callBeforeFilters()
    {
        foreach ($this->beforeFilters as $beforeFilter) {
            $response = call_user_func_array($beforeFilter, [$this->request, $this->response]);

            if ($response !== null) {
                $this->sendResponse($response);
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
