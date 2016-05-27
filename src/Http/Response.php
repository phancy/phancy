<?php
namespace Phancy\Http;

class Response
{
    private $headers;
    private $data;
    private $statusCode;

    public function __construct()
    {
        $this->headers = [];
        $this->data = null;
        $this->statusCode = \Symfony\Component\HttpFoundation\Response::HTTP_OK;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_replace($this->headers, $headers);
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }
}
