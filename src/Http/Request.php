<?php
namespace Phancy\Http;

class Request {
    private $delegate;

    public function __construct()
    {
        $this->delegate = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->delegate, $method], $args);
    }
}