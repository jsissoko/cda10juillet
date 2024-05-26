<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use App\Entity\Utilisateur;

class ActiveUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        // Pas de vérification avant l'authentification
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Utilisateur) {
            return;
        }

        if (!$user->getIsActive()) {
            throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas encore activé. Veuillez vérifier votre email.');
        }
    }
}
