<?php
namespace Phancy;

use Exception;
use Phancy\Http\Response;
use Phancy\Interfaces\Serializer;

class Phancy
{
    private $router;
    private $resources;
    private $request;
    private $response;
    private $beforeFilters;
    private $afterFilters;
    private $serializer;
    private $errorHandler;

    public function __construct()
    {
        $this->resources = [];
        $this->beforeFilters = [];
        $this->afterFilters = [];
        $this->errorHandler = null;
        $this->request = new Http\Request();
        $this->response = new Http\Response();
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
            $router = new Routing\Router();
            $resource->endpoints($router);
            $dispatcher = new Routing\Dispatcher($router);
            $route = $dispatcher->dispatch($this->request->getMethod(), $this->request->getRequestUri());

            if(!is_null($route)) {
                break;
            }
        }

        try {
            $this->callBeforeFilters();
            $response = call_user_func_array($route->getCallback(), [$this->request, $this->response]);
            $this->sendResponse($response);
            $this->callAfterFilters();
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    public function setErrorHandler(callable $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function addBeforeFilter(callable $callback)
    {
        array_push($this->beforeFilters, $callback);
    }

    public function addAfterFilter(callable $callback)
    {
        array_push($this->afterFilters, $callback);
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

            if ($response instanceof Response) {
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

    private function handleError(Exception $e)
    {
        if ($this->errorHandler !== null) {
            $response = call_user_func_array($this->errorHandler, [$e]);
            if ($response instanceof Response) {
                $this->sendResponse($response);
                return;
            }
        }

        throw $e;
    }
}
