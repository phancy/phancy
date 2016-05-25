<?php
namespace Phancy\Serializers;

use Phancy\Interfaces\Serializer;

class JsonSerializer implements Serializer
{
    public function serialize($data)
    {
        return json_encode($data);
    }
}
