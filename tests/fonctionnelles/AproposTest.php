<?php

namespace App\Tests\Fonctionnelles;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutPageTest extends WebTestCase
{
    public function testAboutPageContent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/apropos');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'À propos de Ventalis');
        $this->assertSelectorExists('div.container');
        $this->assertSelectorExists('a[href="' . $client->getContainer()->get('router')->generate('contact') . '"]');
        //  permet de confirmer que la page testée contient un lien vers la page de contact, et que ce lien est correctement formaté selon les règles de routage
        $this->assertSelectorExists('img[src*="apropos.png"]');
    }
}
