<?php

namespace App\Tests\Fonctionnelles;
// tests/Functional/HomepageTest.php


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testHomePageLoadsCorrectly()
    {
        // Crée un nouveau client pour simuler un navigateur
        $client = static::createClient();

        // Exécute une requête GET sur la racine de l'application
        $client->request('GET', '/');

        // Assert que la réponse HTTP a un statut "200 OK"
        $this->assertResponseIsSuccessful();

        // Assert que le contenu de la réponse contient un certain texte
        $this->assertSelectorTextContains('h1', 'Bienvenue chez Ventalis'); // Assurez-vous que le texte correspond à ce que vous avez sur votre page d'accueil
    }
}

