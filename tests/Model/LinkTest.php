<?php

namespace Tests\Wizbii\HateOasBundle\Model;

use PHPUnit\Framework\TestCase;
use Wizbii\HateOasBundle\Model\Link;

class LinkTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function test_it_returns_array_with_good_structure($name, $href, $method)
    {
        $link = new Link($name, $href, $method);

        $this->assertEquals([$name => ['href' =>  $href, 'method' => $method]], $link->toArray());
    }

    public function provideData()
    {
        return [
            ['self', '/api/resources', 'GET'],
            ['createResource', '/api/resources', 'POST'],
            ['updateResource', '/api/resources/resource-id', 'PUT'],
            ['deleteResource', '/api/resources/resource-id', 'DELETE'],
            ['getResource', '/api/resources/resource-id', 'GET'],
        ];
    }
}
