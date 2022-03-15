<?php

namespace Tests\Wizbii\HateOasBundle\Attribute;

use PHPUnit\Framework\TestCase;
use Wizbii\HateOasBundle\Attribute\FromResponse;

class FromResponseTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testGetParameterValue(string $content, string $name, $expectedValue)
    {
        $fromResponse = new FromResponse($name);
        $result = $fromResponse->getParameterValue($content);
        $this->assertEquals($expectedValue, $result);
    }

    public function provideData()
    {
        return [
            ['{"id": "generated-id"}', 'id', 'generated-id'],
            ['{"data": {"id": "generated-id"}}', 'data.id', 'generated-id'],
            ['{"data": {"object": {"anotherObject": {"someProperty": "someValue", "paramToFind": "paramValue"}}}}', 'data.object.anotherObject.paramToFind', 'paramValue'],
        ];
    }
}
