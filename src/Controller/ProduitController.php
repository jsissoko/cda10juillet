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
    public function produitsPage(ProduitRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        $products = $productsRepository->findAll();
        $categories = $categoriesRepository->findAll();

        return $this->render('produits/liste.html.twig', [
            'controller_name' => 'ProduitController',
            'products' => $products,
            'categories' => $categories,
            'selectedCategories' => [], // Initialement aucune catégorie sélectionnée
        ]);
    }

    #[Route('/products', name: 'produit_index')]
    public function index(Request $request, ProduitRepository $produitRepository, CategoriesRepository $categoriesRepository): Response
    {
        // Récupérer les catégories sélectionnées à partir de la requête
        $selectedCategories = $request->query->all('categories');

        // S'assurer que les catégories sélectionnées sont un tableau
        if (!is_array($selectedCategories)) {
            $selectedCategories = [];
        }

        // Debugging: dump the selectedCategories
        dump($selectedCategories);

        // Récupérer les produits filtrés
        $products = $produitRepository->findByCategories($selectedCategories);

        // Récupérer toutes les catégories
        $categories = $categoriesRepository->findAll();

        // Rendre la vue avec les produits et les catégories
        return $this->render('produits/liste.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }
}
