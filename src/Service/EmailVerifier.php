<?php

// src/Service/EmailVerifier.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailVerifier
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendVerificationEmail($emailAddress,$code)
    {
        $email = (new Email())
            ->from('noreply@example.com')  // Changez ceci par votre adresse d'envoi
            ->to($emailAddress)
            ->subject('Please Confirm your Email')
            ->text("Your verification code is: $code");

        $this->mailer->send($email);
    }
}
