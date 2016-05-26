<?php
namespace Phancy\Exceptions;

class HttpMethodNotAllowed extends \Exception
{
    public function __construct($message = '', $code = 405, \Exception $previous = null)
    {
    }
}