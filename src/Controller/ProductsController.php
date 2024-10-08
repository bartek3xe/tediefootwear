<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
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
        $pageNumber = $request->query->get('page', 1);
        $pageNumber = filter_var($pageNumber, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $slugsArray = $this->service->getSlugsInArray($request);
        $paginatedProducts = $this->service->getProductsByCategories($slugsArray, $pageNumber);

        if ($pageNumber > $paginatedProducts['total_pages']) {
            $pageNumber = $paginatedProducts['total_pages'];
        }

        return $this->render('products/index.html.twig', [
            'products' => $paginatedProducts['data'],
            'total_pages' => $paginatedProducts['total_pages'],
            'total_elements' => $paginatedProducts['total_elements'],
            'page_number' => $pageNumber,
            'product_categories' => $categoryRepository->findAll(),
            'selected_categories' => $slugsArray,
            'start_page' => max(1, $pageNumber - 2),
            'end_page' => min($paginatedProducts['total_pages'], $pageNumber + 2),
        ]);
    }

    #[Route('/products/{slug}', name: 'products_show')]
    public function show(Product $product): Response
    {
        return $this->render('products/details.html.twig', [
            'product' => $product,
        ]);
    }
}
