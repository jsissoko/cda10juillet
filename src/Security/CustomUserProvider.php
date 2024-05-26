<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Employe;

class CustomUserProvider implements UserProviderInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Recherche d'abord dans la table des utilisateurs
        $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $identifier]);
        if (!$user) {
            // Si non trouvé, recherche dans la table des employés
            $user = $this->entityManager->getRepository(Employe::class)->findOneBy(['email' => $identifier]);
        }

        // if (!$user) {
        //     throw new UsernameNotFoundException("No user or employee found with email: $identifier");
        // }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->entityManager->getRepository($class)->find($user->security->getId());
    }

    public function supportsClass($class): bool
    {
        return Utilisateur::class === $class || Employe::class === $class;
    }
}
