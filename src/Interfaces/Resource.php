<?php
namespace Phancy\Interfaces;

use Phancy\Routing\Router;

interface Resource
{
    public function endpoints(Router $router);
}
