<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductCategoryRepository $categoryRepository,
    ) {
    }

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (empty($query)) {
            return new JsonResponse([]);
        }

        $products = $this->productRepository->findBySearchQuery($query);
        $categories = $this->categoryRepository->findBySearchQuery($query);

        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'type' => 'product',
                'label' => $product->getName(),
                'link' => $this->generateUrl('app_product_show', ['slug' => $product->getSlug()]),
                'icon' => 'fa-box'
            ];
        }

        foreach ($categories as $category) {
            $results[] = [
                'type' => 'category',
                'label' => $category->getName(),
                'link' => $this->generateUrl('app_category_show', ['slug' => $category->getSlug()]),
                'icon' => 'fa-tags'
            ];
        }

        return new JsonResponse($results);
    }
}
