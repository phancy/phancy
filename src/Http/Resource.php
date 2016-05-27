<?php
namespace Phancy\Http;

use Phancy\Phancy;

abstract class Resource
{
    private $afterFilters = [];
    private $beforeFilters = [];
    private $errorHandler = null;

    public function addAfterFilter(callable $callback)
    {
        array_push($this->afterFilters, $callback);
    }

    public function getAfterFilters()
    {
        return $this->afterFilters;
    }

    public function addBeforeFilter(callable $callback)
    {
        array_push($this->beforeFilters, $callback);
    }

    public function getBeforeFilters()
    {
        return $this->beforeFilters;
    }

    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    public function setErrorHandler(callable $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    abstract public function endpoints(Phancy $phancy);
}
