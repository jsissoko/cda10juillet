<?php

namespace App\Service;

use App\Entity\Commandes;
use App\Entity\CommandeLigne;
use App\Entity\Produit;
use App\Entity\Utilisateur;


class CommandeService
{
    public function creerCommande($user, $cart)
    {
        $commande = new Commandes();
        $commande->setUtilisateur($user);
        $commande->setStatus('En attente');
        $commande->setDate(new \DateTime());

        foreach ($cart as $item) {
            $ligne = new CommandeLigne();
            $ligne->setProduit($item['product']);
            $ligne->setQuantite($item['quantity']);
            $ligne->setPrix($item['product']->getPrix() * $item['quantity']);
            $commande->addCommandeLigne($ligne);
        }

        // Calculer le total ici ou via une méthode dans l'entité Commande
        // Sauvegarder la commande via l'EntityManager

        return $commande;
    }
}
