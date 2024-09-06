<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Service\LanguageService;
use App\Service\Search\CategorySearchService;
use App\Service\Search\ProductSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly ProductSearchService $productSearchService,
        private readonly CategorySearchService $categorySearchService,
    ) {
    }

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (empty($query)) {
            return new JsonResponse([]);
        }

        $productResults = $this->productSearchService->search($query);
        $categoryResults = $this->categorySearchService->search($query);
        $results = array_merge($productResults, $categoryResults);

        return new JsonResponse($results);
    }
}
