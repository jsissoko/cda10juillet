<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Entity\Messages;
use App\Entity\Produit;
use App\Form\MessagesType;
use App\Form\ProduitType;
use App\Repository\MessagesRepository;
use App\Repository\CommandesRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class EmployeController extends AbstractController
{
    private $security;
    private $commandesRepository;



    public function __construct(Security $security, CommandesRepository $commandesRepository)
    {
        $this->security = $security;
        $this->commandesRepository = $commandesRepository;
    }

    #[Route('/employe', name: 'employe_index')]
    public function index(UtilisateurRepository $utilisateurRepository, ProduitRepository $produitRepository): Response
    {
        $user = $this->security->getUser();

        // Vérifiez que l'utilisateur a le rôle ROLE_EMPLOYE
        if (!$this->isGranted('ROLE_EMPLOYE')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        // Récupérer les utilisateurs attribués à cet employé
        $clients = $utilisateurRepository->findClientsByEmploye($user);

        // Récupérer les produits créés par l'employé
        $produits = $produitRepository->findBy(['utilisateur' => $user]);

        return $this->render('employe/index.html.twig', [
            'clients' => $clients,
            'produits' => $produits, // Envoyer les produits à la vue
        ]);
    }

    #[Route('/employe/produit/new', name: 'employe_produit_new')]
    public function newProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Assurez-vous que seulement les employés peuvent accéder à cette fonction
        if (!$this->isGranted('ROLE_EMPLOYE')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette fonctionnalité.');
        }

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->security->getUser();

            // Assurer que l'utilisateur est connecté et est un employé
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour ajouter un produit.');
                return $this->redirectToRoute('login');
            }

            // Définir l'utilisateur du produit
            $produit->setUtilisateur($user);

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès.');

            return $this->redirectToRoute('employe_index');  // Redirige vers la liste des produits, ajustez selon vos besoins
        }

        return $this->render('employe/new_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employe/produit/edit/{id}', name: 'employe_produit_edit')]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du produit
        if ($this->getUser() !== $produit->getUtilisateur()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce produit.');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Produit modifié avec succès.');

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/edit_product.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit
        ]);
    }

    #[Route('/employe/produit/delete/{id}', name: 'employe_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $produit->getUtilisateur()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce produit.');
        }

        $entityManager->remove($produit);
        $entityManager->flush();
        $this->addFlash('success', 'Produit supprimé avec succès.');

        return $this->redirectToRoute('employe_index');
    }



    #[Route('/employe/user/{id}', name: 'user_show')]
    public function show(Utilisateur $client, Request $request, EntityManagerInterface $entityManager, MessagesRepository $messagesRepository, CommandesRepository $commandesRepository): Response
    {
        $message = new Messages();
        $message->setDestinataire($client);

        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setExpediteur($this->getUser());
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Message envoyé avec succès.');
            return $this->redirectToRoute('user_show', ['id' => $client->getId()]);
        }

        // Récupérer les messages échangés entre l'employé et le client
        $messages = $messagesRepository->findMessagesBetween($this->getUser(), $client);

        // Récupérer les commandes en cours pour ce client
        $commandesEnCours = $commandesRepository->findCommandesEnCoursByUtilisateur($client);

        return $this->render('employe/show.html.twig', [
            'client' => $client,
            'messages' => $messages,
            'commandesEnCours' => $commandesEnCours, // Passez les commandes en cours à la vue
            'messageForm' => $form->createView(),
        ]);
    }

    #[Route('/commande/detail/{id}', name: 'commande_detail')]
    public function detail(CommandesRepository $commandesRepository, int $id): Response
    {
        // Utilisez une méthode de repository pour obtenir les détails de la commande, y compris les lignes et produits associés
        $commande = $commandesRepository->findDetailedOrderById($id);

        if (!$commande) {
            throw $this->createNotFoundException('La commande demandée n\'existe pas.');
        }

        // Assurez-vous que le template existe et est bien configuré pour afficher les données retournées
        return $this->render('employe/detail_commande.html.twig', [
            'commande' => $commande
        ]);
    }
}
