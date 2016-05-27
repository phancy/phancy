<?php
namespace Phancy\Exceptions;

class HttpNotFound extends \Exception
{
    public function __construct($message = '', $code = 404, \Exception $previous = null)
    {
    }
}
