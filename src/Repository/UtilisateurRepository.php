<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
* @implements PasswordUpgraderInterface<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
        
    }

    public function findAvailableEmployee(): ?Utilisateur
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.clients', 'c') // 'c' représente les clients de l'employé 'e'
            ->groupBy('e.id') // Grouper par ID d'employé pour permettre le comptage
            ->select('e', 'COUNT(c.id) AS HIDDEN clientCount') // Sélectionner les employés et compter leurs clients
            ->andWhere('e.roles LIKE :role') // Filtrer pour obtenir uniquement les employés
            ->setParameter('role', '%"ROLE_EMPLOYE"%') // Assurez-vous que le rôle est correctement défini
            ->orderBy('clientCount', 'ASC') // Ordonner par le nombre de clients ascendant
            ->setMaxResults(1) // Récupérer seulement le premier résultat
            ->getQuery()
            ->getOneOrNullResult(); // Exécuter la requête et obtenir un seul résultat ou null si aucun employé n'est trouvé
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

}
