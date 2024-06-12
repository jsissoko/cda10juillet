<?php

namespace App\Controller;


use App\Repository\ProduitRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{ 
    #[Route('/produit', name: 'app_produit')]
    public function produits_page(ProduitRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        $products = $productsRepository->findAll();
        $category = $categoriesRepository->findAll();

        return $this->render('produits/liste.html.twig', [
            'controller_name' => 'AccueilController',
            'products' => $products,
            'categorie' => $category

        ]);

    }
    #[Route('/products/filter', name: 'product_filter')]
    public function filter(Request $request, ProduitRepository $productRepository): Response
    {
        $categories = $request->query->get('categories', []);
        $products = $productRepository->findByCategories($categories);

        return $this->render('product/_products.html.twig', [
            'products' => $products,
        ]);
    }
}
