<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use App\Entity\Gnome;

class RESTTestCase  extends WebTestCase
{

    /**
     * @var string
     */
    private $api_prefix = 'http://127.0.0.1:81/api/v1';

    /**
     * @param Client $client
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getDoctrine(Client $client)
    {
        $container = $client->getContainer();

        return $container->get('doctrine')->getManager();
    }

    /**
     * @return string
     */
    protected function getApiPrefix()
    {
        return $this->api_prefix;
    }

    /**
     * @param Client $client
     * @param $name
     * @param $age
     * @param $strength
     * @param $avatar
     * @return Gnome
     */
    protected function createGnome(Client $client, $name, $age, $strength, $avatar)
    {
        $em = $this->getDoctrine($client);

        $gnome = new Gnome();
        $gnome
            ->setName($name)
            ->setAge($age)
            ->setStrength($strength)
            ->setAvatar($avatar)
        ;

        $em->persist($gnome);
        $em->flush();

        return $gnome;
    }

    /**
     * @param Client $client
     * @param $id Gnome ID
     */
    protected function removeGnome(Client $client, $id)
    {
        $em = $this->getDoctrine($client);

        $gnome = $em
            ->getRepository(Gnome::class)
            ->find($id)
        ;

        $em->remove($gnome);
        $em->flush();
    }
}