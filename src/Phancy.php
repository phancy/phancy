<?php
namespace Phancy;

use Exception;
use Phancy\Http\Resource;
use Phancy\Http\Response;
use Phancy\Interfaces\Serializer;

class Phancy
{
    private $router;
    private $resources;
    private $request;
    private $response;
    private $serializer;

    public function __construct()
    {
        $this->resources = [];
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

            if ($route !== null) {
                break;
            }
        }

        // TODO: throw error if we never find a route...

        try {
            $this->callBeforeFilters($resource);
            // TODO: stop execution if before filter returns response...
            $response = call_user_func_array($route->getCallback(), [$this->request, $this->response]);
            $this->callAfterFilters($resource);
            $this->sendResponse($response);
        } catch (Exception $e) {
            $this->handleError($resource, $e);
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

    private function callBeforeFilters(Http\Resource $resource)
    {
        foreach ($resource->getBeforeFilters() as $beforeFilter) {
            $response = call_user_func_array($beforeFilter, [$this->request, $this->response]);

            if ($response instanceof Response) {
                $this->sendResponse($response);
            }
        }
    }

    private function callAfterFilters(Http\Resource $resource)
    {
        foreach ($resource->getAfterFilters() as $afterFilter) {
            call_user_func_array($afterFilter, [$this->request, $this->response]);
        }
    }

    private function handleError(Http\Resource $resource, Exception $e)
    {
        $errorHandler = $resource->getErrorHandler();
        if ($errorHandler !== null) {
            $response = call_user_func_array($errorHandler, [$e]);
            if ($response instanceof Response) {
                $this->sendResponse($response);
                return;
            }
        }

        throw $e;
    }
}
