<?php
namespace Phancy\Interfaces;

use Phancy\Phancy;

interface Resource {
    public function endpoints(Phancy $phancy);
}