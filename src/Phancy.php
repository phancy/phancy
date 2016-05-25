<?php
namespace Phancy;

use Phancy\Interfaces\Serializer;

class Phancy {
    private $resources;
    private $request;
    private $response;

    public function __construct()
    {
        $this->resources = [];
        $this->request = new Http\Request();
        $this->response = new Http\Response();
    }

    public function register(Interfaces\Resource $resource)
    {
        array_push($this->resources, $resource);
    }

    public function process(Serializer $serializer = null)
    {
        foreach ($this->resources as $resource) {
            $resource->endpoints();
        }

        // TODO: dispatch and invoke closure with request and response

        if ($serializer === null) {
            $serializer = new Serializers\JsonSerializer();
        }

        $this->response->send($serializer);
    }
}