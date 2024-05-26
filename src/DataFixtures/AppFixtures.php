<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Categorie;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer 20 fixtures pour Produit
        for ($i = 1; $i <= 30; $i++) {
                $produit = new Produit();
                $produit->setnom("Produit $i");
                $produit->setPrix(rand(10, 100)); // Prix aléatoire entre 10 et 100
                $produit->setRefProd("REF$i");
                $produit->setQuantite(rand(1, 100));
                $produit->setStatutProduit("Genre $i");
                $produit->setInfoProduit("Informations sur le produit $i.");

                
             $manager->persist($produit);
        }

       
        // Créer 20 fixtures pour CategorieProduit
        for ($i = 1; $i <= 6; $i++) {
            $categorie = new Categorie();
            $categorie->setNomCateg("Catégorie $i");
            // ... définissez d'autres propriétés si nécessaire
            $manager->persist($categorie);
        }

        // Persistez les objets dans la base de données
        $manager->flush();
    }
}
