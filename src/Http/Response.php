<?php
namespace Phancy\Http;

class Response {
    private $response;

    public function __construct()
    {
        $this->response = new \Symfony\Component\HttpFoundation\Response();
    }

    // TODO: add methods from Nancy to wrap Symphony's Response methods
}