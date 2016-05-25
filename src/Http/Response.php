<?php
namespace Phancy\Http;

use Phancy\Interfaces\Serializer;

class Response
{
    private $delegate;
    private $data;

    public function __construct()
    {
        $this->delegate = new \Symfony\Component\HttpFoundation\Response();
        $this->data = null;
    }

    public function setHeader($key, $value)
    {
        $this->delegate->headers->set($key, $value);
    }

    public function setHeaders(array $headers)
    {
        $this->delegate->headers->add($headers);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setStatusCode($code)
    {
        $this->delegate->setStatusCode($code);
    }

    public function send(Serializer $serializer)
    {
        $this->delegate->setContent($serializer->serialize($this->data));
        $this->delegate->send();
    }
}
