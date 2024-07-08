<?php
// src/Controller/ForgotPasswordController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\ForgotPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Utilisateur; // Assurez-vous que le c

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique d'envoi de l'email de réinitialisation
        }

        return $this->render('forgot_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneByResetToken($token);

        if (!$utilisateur || $utilisateur->getTokenExpiry() < new \DateTime()) {
            $this->addFlash('error', 'The password reset token is invalid or expired.');
            return $this->redirectToRoute('app_login');
        }

        // Affichez et gérez le formulaire de nouveau mot de passe ici
    }


}
