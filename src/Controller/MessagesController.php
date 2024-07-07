<?php
namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MessagesController extends AbstractController
{
    private $entityManager;
    private $messagesRepository;
    private $utilisateurRepository;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, MessagesRepository $messagesRepository, UtilisateurRepository $utilisateurRepository, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->messagesRepository = $messagesRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->security = $security;
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

    #[Route('/messages/send', name: 'send_message_form')]
    public function sendMessageForm(Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
                return $this->redirectToRoute('app_login');
            }

            if (in_array('ROLE_USER', $user->getRoles())) {
                // Utilisateur envoie un message à son employé
                $employe = $this->utilisateurRepository->findEmployeByClient($user);
                if (!$employe) {
                    $this->addFlash('error', 'Aucun employé n\'est assigné à votre compte.');
                    return $this->redirectToRoute('app_messages');
                }
                $destinataire = $employe;
            } else {
                $this->addFlash('error', 'Rôle utilisateur non reconnu.');
                return $this->redirectToRoute('app_login');
            }

            $message->setDestinataire($destinataire);
            $message->setExpediteur($user);
        
            // Enregistrer le message dans la base de données
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash('success', 'Message envoyé avec succès.');
            return $this->redirectToRoute('app_messages');
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
    public function replyMessage(Request $request, int $id): Response
    {
        $message = $this->entityManager->getRepository(Messages::class)->find($id);

        if (!$message) {
            throw $this->createNotFoundException('Le message n\'existe pas.');
        }

        $replyMessage = new Messages();
        $replyMessage->setTitle('Re: ' . $message->getTitle());
        $replyMessage->setMessage($request->request->get('response'));
        $replyMessage->setExpediteur($this->getUser());
        $replyMessage->setDestinataire($message->getExpediteur());

        $this->entityManager->persist($replyMessage);
        $this->entityManager->flush();

        $this->addFlash('success', 'Votre réponse a été envoyée.');
        return $this->redirectToRoute('app_messages');
    }
}
