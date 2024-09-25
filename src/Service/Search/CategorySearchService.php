<?php

declare(strict_types=1);

namespace App\Service\Search;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Service\LanguageService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategorySearchService
{
    public function __construct(
        private readonly ProductCategoryRepository $categoryRepository,
        private readonly ProductRepository $productRepository,
        private readonly LanguageService $languageService,
        private readonly RouterInterface $router,
        private readonly TranslatorInterface $translator,
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
        /** @var ProductCategory $category */
        foreach ($categories as $category) {
            $results[] = [
                'type' => 'category',
                'title' => sprintf(
                    '%s: %s',
                    $this->translator->trans('category.singular'),
                    $category->getName()[$this->languageService->getLocale()],
                ),
                'product_count' => $this->productRepository->countProductsByCategory($category),
                'link' => $this->router->generate('app_products', ['categories' => $category->getSlug()]),
                'icon' => 'fas fa-bookmark',
            ];
        }

        return $results;
    }
}
