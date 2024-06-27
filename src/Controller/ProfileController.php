<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Repository\CommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ChangePasswordFormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserPasswordType;


    #[Route('/profile')]
    class ProfileController extends AbstractController
    {
        
    #[Route('/', name: 'app_profile_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return $this->render('profile/index.html.twig', [
            'utilisateur' => $user,
        ]);
    }



    #[Route('/mes-commandes', name: 'app_mes_commandes', methods: ['GET'])]
    public function mesCommandes(CommandesRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $commandes = $commandeRepository->findBy(['utilisateur' => $user]);

        return $this->render('profile/mes_commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }


    #[Route('/detail-profil', name: 'app_detail_profil')]
    public function detail(): Response
    {
        $user = $this->getUser(); // Assurez-vous que l'utilisateur est connecté.

        return $this->render('profile/detail.html.twig', [
            'utilisateur' => $user,
        ]);
    }
    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            return $this->redirectToRoute('profile_show');
        }
    
        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/change-password/{id}', name: 'app_change_password')]
    public function changePassword(Request $request,Utilisateur $utilisateur, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $newPassword);
            $utilisateur->setPassword($hashedPassword);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}   


    