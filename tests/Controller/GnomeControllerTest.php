<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\RESTTestCase;
use Symfony\Component\HttpFoundation\Response;

class GnomeControllerTest extends RESTTestCase
{
    public function testGetEmptyGnomesList()
    {
        $client = static::createClient();
        $client->request('GET', $this->getApiPrefix() . '/gnomes');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testGetNotExistingGnome()
    {
        $client = static::createClient();
        $client->request('GET', $this->getApiPrefix() . '/gnomes/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testCreateGnomeWithEmptyData()
    {
        $data = [
            'name' => '',
            'age' => null,
            'strength' => null,
            'avatar' => '',
        ];

        $client = static::createClient();
        $client->request('POST', $this->getApiPrefix() . '/gnomes', $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testUpdateNotExistingGnome()
    {
        $client = static::createClient();
        $client->request('PUT', $this->getApiPrefix() . '/gnomes/9999');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testUpdateGnomeWithEmptyData()
    {
        $data = [
            'name' => '',
            'age' => null,
            'strength' => null,
            'avatar' => '',
        ];

        $client = static::createClient();

        $gnome = $this->createGnome($client, 'Test Gnome', 10, 50, 'avatar.png');
        $client->request('PUT', $this->getApiPrefix() . '/gnomes/' . $gnome->getId(), $data);
        $this->removeGnome($client, $gnome->getId());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNotExistingGnome()
    {
        $client = static::createClient();
        $client->request('DELETE', $this->getApiPrefix() . '/gnomes/9999');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testGetGnomesListSuccessfully()
    {
        $client = static::createClient();
        $g1 = $this->createGnome($client, 'Test Gnome', 10, 50, 'avatar.png');
        $g2 = $this->createGnome($client, 'Test Gnome 2', 12, 55, 'avatar2.png');

        $client->request('GET', $this->getApiPrefix() . '/gnomes');
        $response = $client->getResponse();

        $this->removeGnome($client, $g1->getId());
        $this->removeGnome($client, $g2->getId());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(2, count(json_decode($response->getContent())));
    }

    public function testGetSingleGnomeSuccessfully()
    {
        $client = static::createClient();
        $gnome = $this->createGnome($client, 'Test Gnome', 10, 50, 'avatar.png');

        $client->request('GET', $this->getApiPrefix() . '/gnomes/' . $gnome->getId());
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent());

        $this->removeGnome($client, $gnome->getId());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Test Gnome', $responseContent->name);
        $this->assertEquals(10, $responseContent->age);
        $this->assertEquals(50, $responseContent->strength);
        $this->assertEquals('avatar.png', $responseContent->avatar);
    }

    public function testCreateGnomeSuccessfully()
    {
        $data = [
            'name' => 'Test Gnome',
            'age' => 10,
            'strength' => 50,
            'avatar' => 'avatar.png'
        ];

        $client = static::createClient();
        $client->request('POST', $this->getApiPrefix() . '/gnomes', $data);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotEmpty($response->headers->get('location'));
        $this->assertTrue(is_int($response->headers->get('X-Resource-ID')));

        $this->removeGnome($client, $response->headers->get('X-Resource-ID'));
    }

    public function testUpdateGnomeSuccessfully()
    {
        $data = [
            'name' => 'New Gnome Name',
            'age' => 15,
            'strength' => 75,
            'avatar' => 'avatarNEW.png'
        ];

        $client = static::createClient();
        $gnome = $this->createGnome($client, 'Test Gnome', 10, 50, 'avatar.png');
        $client->request('PUT', $this->getApiPrefix() . '/gnomes/' . $gnome->getId(), $data);
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('New Gnome Name', $responseContent->name);
        $this->assertEquals(15, $responseContent->age);
        $this->assertEquals(75, $responseContent->strength);
        $this->assertEquals('avatarNEW.png', $responseContent->avatar);

        $this->removeGnome($client, $gnome->getId());
    }
}