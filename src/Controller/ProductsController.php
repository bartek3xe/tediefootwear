<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function filterProducts(Request $request, ProductRepository $productRepository, ProductCategoryRepository $categoryRepository): Response
    {
        $slugs = $request->query->get('categories', '');
        $slugsArray = $slugs ? explode(',', $slugs) : [];

        if (!empty($slugsArray)) {
            $categories = $categoryRepository->findBy(['slug' => $slugsArray]);

            $products = $productRepository->findByCategories($categories);
        } else {
            $products = $productRepository->findAll();
        }

        $allCategories = $categoryRepository->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'product_categories' => $allCategories,
            'selected_categories' => $slugsArray,
        ]);
    }
}
