<?php

namespace App\Tests\Fonctionnelles;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FonctionnellesBasicTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

    }
}
