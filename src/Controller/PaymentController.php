<?php
// src/Controller/PaymentController.php
// src/Controller/PaymentController.php
namespace App\Controller;

use App\Entity\Commandes;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager,)
    {
        $this->entityManager = $entityManager;

    }
    #[Route('/checkout/{matricule_cmd}', name: 'checkout')]
    public function checkout($matricule_cmd, EntityManagerInterface $entityManager): Response
    {
        $stripeSecretKey = 'sk_test_51PNdb32KLLGJoxlC5nZ3VGkchZk4vhArK0Xx7kHc3rIyeNXAntMbAjTUxNjoiXIEtj6bDTO5iJHW2r5GpFMGwBRK00LxgpA1hA'; // Remplacez par votre clé secrète Stripe
        $stripePublicKey = 'pk_test_51PNdb32KLLGJoxlCXxtlzsj7HRMyfbXCnX0C2iGktzjI4x8CLEhYr8uT1ehAPWLg6poJOWIDU0jTBWFQ97Rw6UsU00E97oE3MW'; // Remplacez par votre clé publique Stripe

        Stripe::setApiKey($stripeSecretKey);

        $commande = $entityManager->getRepository(Commandes::class)->findOneBy(['matricule_cmd' => $matricule_cmd]);

        if (!$commande) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }

        try {
            $checkout_session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Votre produit',
                        ],
                        'unit_amount' => $commande->getTotal() * 100, // Montant en cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('success', ['matricule_cmd' => $matricule_cmd], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('cancel', ['matricule_cmd' => $matricule_cmd], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->render('payment/redirect.html.twig', [
                'sessionId' => $checkout_session->id,
                'stripePublicKey' => $stripePublicKey,
            ]);
        } catch (\Exception $e) {
            return new Response('Erreur lors de la création de la session Stripe: ' . $e->getMessage());
        }
    }

    #[Route('/success/{matricule_cmd}', name: 'success')]
    public function success($matricule_cmd,EntityManagerInterface $entityManager): Response
    {
        $commande = $this->entityManager->getRepository(Commandes::class)->findOneBy(['matricule_cmd' => $matricule_cmd]);

        if ($commande) {
            $commande->setStatus('Payé'); // Mettez à jour le statut ici
            $this->entityManager->persist($commande);
            $this->entityManager->flush();
        }

        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel', name: 'cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
