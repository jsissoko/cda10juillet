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
// use Doctrine\ORM\Mapping\Entity;
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

            // $employe = $user->getNom();  // Récupérer l'employé assigné
            // if (!$employe) {
            //     $this->addFlash('error', 'Aucun employé n’est assigné à votre compte.');
            //     return $this->redirectToRoute('app_messages');  // Rediriger vers la liste des messages
            // }
            

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


    #[Route('/messages/show/{id}', name: 'message_show')]
    public function showMessage(int $id): Response
    {
        $message = $this->entityManager->getRepository(Messages::class)->find($id);

        if (!$message) {
            throw $this->createNotFoundException('Le message demandé n\'existe pas.');
        }

        return $this->render('messages/show.html.twig', [
            'message' => $message
        ]);
    }

    #[Route('/messages/reply/{id}', name: 'message_reply', methods: ['POST'])]
    public function replyMessage(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $message = $entityManager->getRepository(Messages::class)->find($id);

        if (!$message) {
            throw $this->createNotFoundException('Le message n\'existe pas.');
        }

        $replyMessage = new Messages();
        $replyMessage->setTitle('Re: ' . $message->getTitle());
        $replyMessage->setMessage($request->request->get('response'));
        $replyMessage->setExpediteur($this->getUser());
        $replyMessage->setDestinataire($message->getExpediteur());

        $entityManager->persist($replyMessage);
        $entityManager->flush();

        $this->addFlash('success', 'Votre réponse a été envoyée.');
        return $this->redirectToRoute('app_messages');
    }


//     // src/Controller/MessagesController.php

// #[Route('/messages/send-to-employe', name: 'send_message_to_employe')]
// public function sendMessageToEmploye(Request $request, EntityManagerInterface $entityManager): Response
// {
//     $user = $this->getUser();  // Assurez-vous que cela retourne un Utilisateur

//     if (!$user) {
//         $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
//         return $this->redirectToRoute('app_login');
//     }

//     $employe = $user->getEmploye();
//     if (!$employe) {
//         $this->addFlash('error', 'Aucun employé n’est assigné à votre compte.');
//         return $this->redirectToRoute('dashboard');
//     }

//     $message = new Messages();
//     $message->setExpediteur($user);
//     $message->setDestinataire($employe);
//     // Logique pour traiter le formulaire de message
// }

}
