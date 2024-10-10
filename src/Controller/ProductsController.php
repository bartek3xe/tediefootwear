<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductCategoryRepository;
use App\Service\ProductCategoryService;
use App\Service\ProductControllerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class ProductsController extends AbstractController
{
    public function __construct(
        private readonly ProductControllerService $controllerService,
        private readonly ProductCategoryService $categoryService,
    ) {
    }

    #[Route('/products', name: 'products')]
    public function filterProducts(Request $request, ProductCategoryRepository $categoryRepository): Response
    {
        $data = $this->controllerService->handleProductRequest($request);
        $data['product_categories'] = $categoryRepository->findAll();
        $data['selected_categories'] = $this->categoryService->getSlugsInArray($request);

        return $this->render('products/index.html.twig', $data);
    }

    #[Route('/products/{slug}', name: 'products_show')]
    public function show(Product $product): Response
    {
        return $this->render('products/details.html.twig', [
            'product' => $product,
        ]);
    }
}
