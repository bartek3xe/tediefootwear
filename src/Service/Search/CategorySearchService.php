<?php

declare(strict_types=1);

namespace App\Service\Search;

use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Service\LanguageService;
use Symfony\Component\Routing\RouterInterface;

class CategorySearchService
{

    public function __construct(
        private readonly ProductCategoryRepository $categoryRepository,
        private readonly ProductRepository $productRepository,
        private readonly LanguageService $languageService,
        private readonly RouterInterface $router,
    ) {
    }

    public function search(string $query): array
    {
        $categories = $this->categoryRepository->findBySearchQuery($query, $this->languageService->getLocale());

        return $this->getResults($categories);
    }

    private function getResults(array $categories): array
    {
        $results = [];
        foreach ($categories as $category) {
            $results[] = [
                'type' => 'category',
                'product_count' => $this->productRepository->countProductsByCategory($category),
                'link' => $this->router->generate('app_products', ['categories' => $category->getSlug()]),
                'icon' => 'fa-tags'
            ];
        }

        return $results;
    }
}
