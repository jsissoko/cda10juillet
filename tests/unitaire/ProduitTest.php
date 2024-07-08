<?php

namespace App\Tests\Unitaire;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProduitTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCreateProduit()
    {
        $produit = new Produit();
        $produit->setNom('Parfum Exquis');
        $produit->setPrix(99.99);
        $produit->setQuantite(10);
        $produit->setInfoProduit('Un parfum frais et agréable.');
        $produit->setStatutProduit(true);
        $produit->setRefProd('REF12345');
        $produit->setImage('path_to_image.jpg');
        
        // Simuler un utilisateur (ajoutez une méthode ou une entité Utilisateur selon vos besoins)
        // $produit->setUtilisateur($utilisateur);

        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        $produitRetrieved = $this->entityManager->getRepository(Produit::class)->find($produit->getId());
        
        $this->assertNotNull($produitRetrieved);
        $this->assertEquals('Parfum Exquis', $produitRetrieved->getNom());
        $this->assertEquals(99.99, $produitRetrieved->getPrix());
        $this->assertEquals(10, $produitRetrieved->getQuantite());
        $this->assertEquals('Un parfum frais et agréable.', $produitRetrieved->getInfoProduit());
        $this->assertTrue($produitRetrieved->isStatutProduit());
        $this->assertEquals('REF12345', $produitRetrieved->getRefProd());
        $this->assertEquals('path_to_image.jpg', $produitRetrieved->getImage());

        // Cleanup
        $this->entityManager->remove($produitRetrieved);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // éviter les fuites de mémoire
        $this->entityManager->close(); 
        $this->entityManager = null;
    }
}
