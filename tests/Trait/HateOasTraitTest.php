<?php

namespace Wizbii\HateOasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Wizbii\HateOasBundle\Fixtures\ExtendsAbstractController;
use Tests\Wizbii\HateOasBundle\Fixtures\NotAbstractController;

class HateOasTraitTest extends KernelTestCase
{
    public function test_it_throws_exception_if_controller_does_not_extends_abstract_controller()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('"HateOasTrait" should be used in a class that extends AbstractController');

        $controller = new NotAbstractController();
        $controller->getAllAction();
    }

    public function test_it_throws_exception_if_controller_returns_array()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The response should be encapsulated in an object if it returns many objects');

        $container = $this->getContainer();

        $controller = new ExtendsAbstractController();
        $controller->setContainer($container);
        $controller->getAllAction();
    }

    public function test_it_returns_same_response_content_if_no_link_added()
    {
        $container = $this->getContainer();

        $controller = new ExtendsAbstractController();
        $controller->setContainer($container);
        $response = $controller->getWithoutLink();
        $this->assertEquals(['data' => 'response with no link'], json_decode($response->getContent(), true));
    }

    public function test_it_returns_response_with_links_added()
    {
        $container = $this->getContainer();

        $controller = new ExtendsAbstractController();
        $controller->setContainer($container);
        $response = $controller->getWithLinks();
        $expected = [
            'data' => 'response with no link',
            '_links' => [
                'getAll' => ['href' => '/api/other-resources', 'method' => 'GET'],
                'create' => ['href' => '/api/other-resources', 'method' => 'POST'],
                'update' => ['href' => '/api/other-resources/{id}', 'method' => 'PUT'],
            ],
        ];

        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }
}
