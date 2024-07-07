<?php

// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Form\VerificationFormType;
use App\Repository\UtilisateurRepository;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private $utilisateurRepository;
    private EmailVerifier $emailVerifier;

    public function __construct(UtilisateurRepository $utilisateurRepository ,EmailVerifier $emailVerifier)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $verificationCode = rand(000000, 999999);
            $user->setVerificationCode($verificationCode);

            $employe = $this->utilisateurRepository->findAvailableEmployee();
            if ($employe) {
                $user->setEmploye($employe);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->emailVerifier->sendVerificationEmail($user->getEmail(), $user->getVerificationCode());
            $this->addFlash('success', 'To finalize the creation of your account, please enter the verification code sent to your email.');

            return $this->redirectToRoute('email_confirmation');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'email_confirmation')]
    public function confirmEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VerificationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]); // Assuming email is part of your VerificationFormType

            if ($user && $user->getVerificationCode() == $data['verificationCode']) {
                $user->setIsActive(true);
                $entityManager->flush();

                $this->addFlash('success', 'Your email has been verified and your account is now active.');
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', 'Invalid verification code.');
            }
        }

        return $this->render('registration/confirmation_email.html.twig', [
            'verificationForm' => $form->createView(),
        ]);

        return $this->redirectToRoute('email_confirmation');

    }
}
