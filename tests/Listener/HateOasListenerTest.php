<?php

namespace Tests\Wizbii\HateOas\Listener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HateOasListenerTest extends WebTestCase
{
    public function test_it_does_nothing_if_request_is_not_json()
    {
        $client = static::createClient();
        $client->request('GET', '/auto/api/resources');

        $this->assertResponseIsSuccessful();
    }

    public function test_it_returns_links_if_attribute_generateAllHateOasLink_is_set()
    {
        $client = static::createClient();
        $client->jsonRequest('GET', '/auto/api/resources');
        $response = $client->getResponse();

        $expected = ['_links' => [
            'self' => ['href' => '/auto/api/resources', 'method' => 'GET'],
            'auto_create' => ['href' => '/auto/api/resources', 'method' => 'POST'],
            'auto_update' => ['href' => '/auto/api/resources/{id}', 'method' => 'PUT'],
            'put_auto_create_or_update' => ['href' => '/auto/api/resources', 'method' => 'PUT'],
            'post_auto_create_or_update' => ['href' => '/auto/api/resources', 'method' => 'POST'],
            'auto_delete' => ['href' => '/auto/api/resources/{id}', 'method' => 'DELETE'],
        ]];

        $this->assertResponseIsSuccessful();

        $this->assertEquals($expected, json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function test_it_returns_links_if_attribute_routeLink_is_set()
    {
        $client = static::createClient();
        $client->jsonRequest('GET', '/manual/api/resources/resource-id');
        $response = $client->getResponse();

        $expected = ['_links' => [
            'self' => ['href' => '/manual/api/resources/resource-id', 'method' => 'GET'],
            'update' => ['href' => '/manual/api/resources/resource-id', 'method' => 'PUT'],
            'delete' => ['href' => '/manual/api/resources/resource-id', 'method' => 'DELETE'],
            'create' => ['href' => '/manual/api/resources', 'method' => 'POST'],
        ]];

        $this->assertResponseIsSuccessful();

        $this->assertEquals($expected, json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function test_it_returns_links_if_attribute_routeLink_is_set_on_post_request()
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/manual/api/resources');
        $response = $client->getResponse();

        $expected = [
            'data' => [
                'id' => 'generated-id',
            ],
            '_links' => [
                'self' => ['href' => '/manual/api/resources', 'method' => 'POST'],
                'update' => ['href' => '/manual/api/resources/generated-id', 'method' => 'PUT'],
                'delete' => ['href' => '/manual/api/resources/generated-id', 'method' => 'DELETE'],
            ],
        ];

        $this->assertResponseIsSuccessful();

        $this->assertEquals($expected, json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR));
    }
}
