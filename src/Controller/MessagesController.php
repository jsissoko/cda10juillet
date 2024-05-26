<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessagesRepository;
use App\Entity\Messages;
use App\Entity\Utilisateur;
use App\Form\MessagesType;
use Symfony\Component\HttpFoundation\Request;

class MessagesController extends AbstractController
{
    private $entityManager;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $entityManager, MessagesRepository $messagesRepository)
    {
        $this->entityManager = $entityManager;
        $this->messagesRepository = $messagesRepository;
    }

    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {
        $receivedMessages = $this->messagesRepository->findBy(['destinataire' => $this->getUser()]);
        $sentMessages = $this->messagesRepository->findBy(['expediteur' => $this->getUser()]);

        return $this->render('messages/index.html.twig', [
            'received_messages' => $receivedMessages,
            'sent_messages' => $sentMessages,
        ]);
    }

    // src/Controller/MessagesController.php

    #[Route('/messages/send', name: 'send_message_form')]
    public function sendMessageForm(Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();
            
            // Vérifier si l'utilisateur est connecté
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
                return $this->redirectToRoute('app_login');  // Rediriger vers la page de connexion
            }

            $message->setExpediteur($user);
           
            // Enregistrer le message dans la base de données
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash('success', 'Message envoyé avec succès.');
            return $this->redirectToRoute('app_messages');  // Rediriger vers la liste des messages
        }

        return $this->render('messages/send.html.twig', [
            'messageForm' => $form->createView(),
        ]);
    }

}
