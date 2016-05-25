<?php
namespace Phancy\Http;

class Request {
    private $request;

    public function __construct()
    {
        $this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }

    // TODO: add methods from Nancy to wrap Symphony's Request methods
}