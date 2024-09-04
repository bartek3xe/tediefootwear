<?php

declare(strict_types=1);

namespace App\Service\Search;

use App\Entity\File;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\LanguageService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductSearchService
{
    private const DEFAULT_PHOTO_FILE_PATH = '/build/images/default-slipper.webp';

    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly LanguageService $languageService,
        private readonly RouterInterface $router,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function search(string $query): array
    {
        $products = $this->productRepository->findBySearchQuery($query, $this->languageService->getLocale());

        return $this->getResults($products);
    }

    private function getResults(array $products): array
    {
        $results = [];
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var File $photo */
            $photo = $product->getFiles()->first() ?: null;
            $results[] = [
                'type' => 'product',
                'title' => $product->getName()[$this->languageService->getLocale()],
                'link' => $this->router->generate('app_products_show', ['slug' => $product->getSlug()]),
                'photo' => $photo?->getFilepath() ?? self::DEFAULT_PHOTO_FILE_PATH,
                'is_new' => $product->isNew(),
                'is_top' => $product->isTop(),
            ];
        }

        return $results;
    }
}
