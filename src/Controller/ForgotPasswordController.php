<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ForgotPasswordController extends AbstractController
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $user->setResetToken($token);
                $user->setTokenExpiry(new \DateTime('+1 hour'));
                $entityManager->flush();

                $url = $this->urlGenerator->generate('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $message = (new Email())
                    ->from('noreply@example.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html("Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href='$url'>$url</a>");

                $mailer->send($message);

                $this->addFlash('success', 'Un email de réinitialisation a été envoyé à votre adresse.');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Aucun compte n\'est associé à cette adresse email.');
        }

        return $this->render('forgot_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getTokenExpiry() < new \DateTime()) {
            $this->addFlash('error', 'Le jeton de réinitialisation du mot de passe est invalide ou a expiré.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $user->setResetToken(null); // Invalidate the token
            $user->setTokenExpiry(null); // Clear the token expiry
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
