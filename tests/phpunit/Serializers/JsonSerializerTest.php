<?php
namespace Phancy\Serializers;

use PHPUnit_Framework_TestCase;

function json_encode($data)
{
    return $data === 'data' ? 'json-encoded-data' : null;
}

class JsonSerializerTest extends PHPUnit_Framework_TestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = new JsonSerializer();
    }

    public function testImplementsSerializerInterface()
    {
        $this->assertInstanceOf(\Phancy\Interfaces\Serializer::class, $this->subject);
    }

    public function testSerializeReturnsJsonEncodedData()
    {
        $this->assertEquals('json-encoded-data', $this->subject->serialize('data'));
    }
}