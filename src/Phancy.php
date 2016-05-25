<?php
namespace Phancy;

class Phancy {
    private $resources;

    public function __construct()
    {
        $this->resources = [];
    }

    public function register(Interfaces\Resource $resource)
    {
        array_push($this->resources, $resource);
    }
}