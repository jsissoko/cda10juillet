<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\CommandeLigne;
use App\Form\CommandesType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CartController extends AbstractController
{

    #[Route('/mon-panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
           'cart' => $cartService->getTotal(),
           'cartDetails' => $cartService->getCartDetails()
        ]);
    }
 
 
    #[Route('/mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id): Response
    {
        $cartService->addToCart($id);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/mon-panier/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService, int $id): Response
    {
        $cartService->removeToCart($id);

        return $this->redirectToRoute('cart_index');
    }



    #[Route('/mon-panier/decrease/{id<\d+>}', name: 'cart_decrease')]
    public function decrease(CartService $cartService, $id): RedirectResponse
    {
        $cartService->decrease($id);

        return $this->redirectToRoute('cart_index');
    }



    #[Route('/mon-panier/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('shop_index');
    }

    #[Route('/mon-panier/commander', name: 'cart_commander')]
public function commander(Request $request, CartService $cartService, EntityManagerInterface $entityManager): Response
{
    $commande = new Commandes();
    $form = $this->createForm(CommandesType::class, $commande);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $this->getUser();
        $cart = $cartService->getCartDetails();

        $commande->setUtilisateur($user);
        $commande->setStatus('En attente');
        $commande->setDate(new \DateTime());

        $total = 0;
        foreach ($cart as $item) {
            $ligne = new CommandeLigne();
            $ligne->setProduit($item['product']);
        //    dd( $ligne->setProduit($item['product']));
            $ligne->setQuantite($item['quantity']);
            $ligne->setPrix($item['product']->getPrix() * $item['quantity']);
            $ligne->setCommande($commande);
            // dd($ligne);
            $entityManager->persist($ligne);
            $total += $ligne->getPrix();
        }
        $commande->setTotal($total);

        $entityManager->persist($commande);
        $entityManager->flush();

        $cartService->removeCartAll();

        return $this->redirectToRoute('commande_success');
    }

    return $this->render('cart/commander.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/commande/success', name: 'commande_success')]
    public function success(): Response
    {
        return $this->render('cart/success.html.twig');
    }

}
