<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Service\ProductCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class ProductsController extends AbstractController
{
    public function __construct(private readonly ProductCategoryService $service)
    {
    }

    #[Route('/products', name: 'products')]
    public function filterProducts(Request $request, ProductCategoryRepository $categoryRepository): Response
    {
        $slugsArray = $this->service->getSlugsInArray($request);
        $allCategories = $categoryRepository->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $this->service->getProductsByCategories($request, $slugsArray),
            'product_categories' => $allCategories,
            'selected_categories' => $slugsArray,
        ]);
    }
}
