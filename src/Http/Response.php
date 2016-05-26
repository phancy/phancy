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
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->delegate->headers->add($headers);
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setStatusCode($code)
    {
        $this->delegate->setStatusCode($code);
        return $this;
    }

    public function send(Serializer $serializer)
    {
        $this->delegate->setContent($serializer->serialize($this->data));
        $this->delegate->send();
    }
}
