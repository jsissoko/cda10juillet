<?php

namespace App\EventSubscriber;

use App\Entity\Messages;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            InteractiveLoginEvent::class => 'onUserLogin',
        ];
    }

    public function onUserLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Vérifiez si l'utilisateur est une instance de Utilisateur
        if (!$user instanceof Utilisateur) {
            return;
        }

        // Vérifiez si l'utilisateur est authentifié pour la première fois
        if ($user->getLastLogin() === null) {
            $this->sendWelcomeMessage($user);
        }

        // Mettre à jour la date de dernière connexion
        $user->setLastLogin(new \DateTime());
        $this->entityManager->flush();
    }

    private function sendWelcomeMessage(Utilisateur $user)
    {
        // Récupérer l'employé affilié à cet utilisateur
        $employe = $user->getEmploye();

        if (!$employe) {
            return;
        }

        $message = new Messages();
        $message->setExpediteur($employe);
        $message->setDestinataire($user);
        $message->setTitle('Bienvenue');
        $message->setMessage('Bonjour et bienvenue dans notre application Je suis ' . $employe->getNom()  .' votre Conseiller j. Si vous avez des questions, n\'hésitez pas à me contacter.');
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}
