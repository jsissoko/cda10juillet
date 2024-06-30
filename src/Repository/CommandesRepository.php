<?php

// src/Repository/CommandesRepository.php

namespace App\Repository;

use App\Entity\Commandes;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    /**
     * Récupère toutes les commandes en cours pour un utilisateur donné.
     *
     * @param Utilisateur $utilisateur L'utilisateur pour lequel les commandes en cours sont requises.
     * @return Commande[] Retourne un tableau d'objets Commande.
     */
    public function findCommandesEnCoursByUtilisateur(Utilisateur $utilisateur)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.utilisateur = :utilisateur')
            ->andWhere('c.status = :status')
            ->setParameters([
                'utilisateur' => $utilisateur,
                'status' => 'Payé' 
            ])
            ->orderBy('c.date', 'DESC') // Trier par date de commande, modifiez selon les besoins
            ->getQuery()
            ->getResult();
    }




// src/Repository/CommandesRepository.php

public function findDetailedOrderById($id)
{
    return $this->createQueryBuilder('c')
        ->select('c', 'cl', 'p')
        ->leftJoin('c.commandeLignes', 'cl')
        ->leftJoin('cl.produit', 'p')
        ->where('c.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
}


}
// Crée un constructeur de requête pour l'entité Commande. c est un alias utilisé pour référencer cette entité dans les autres parties de la requête.
// select('c', 'cl', 'p'):

// Spécifie les entités à sélectionner : c pour les commandes, cl pour les lignes de commande, et p pour les produits. Cela permet de charger toutes ces entités en une seule requête pour optimiser les performances et réduire le nombre de requêtes à la base de données.
// leftJoin('c.commandeLignes', 'cl'):

// Effectue une jointure gauche avec les lignes de commande associées à chaque commande. commandeLignes est le nom de la propriété dans l'entité Commande qui référence les lignes de commande. cl est un alias pour les lignes de commande.
// leftJoin('cl.produit', 'p'):

// Effectue une jointure gauche avec l'entité Produit pour chaque ligne de commande. Cela permet d'accéder aux détails du produit directement à partir de chaque ligne de commande.
// where('c.id = :id') et setParameter('id', $id):

// Spécifie la condition pour filtrer la commande par son identifiant. Le paramètre :id est lié à la variable $id fournie à la fonction, permettant de rechercher une commande spécifique.
// getOneOrNullResult():

// Exécute la requête et retourne un seul résultat ou null si aucun résultat n'est trouvé. Cela est utile pour garantir qu'une seule commande est retournée, ou aucun résultat si l'identifiant de commande n'est pas trouvé
