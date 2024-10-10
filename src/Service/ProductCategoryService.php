<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProductCategory;
use App\Exception\NotFoundException;
use App\Repository\ProductCategoryRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductCategoryService
{
    public function __construct(
        private readonly ProductCategoryRepository $productCategoryRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CacheInterface $cache,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function save(ProductCategory $productCategory): bool
    {
        return $this->productCategoryRepository->save($productCategory);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(int $id): bool
    {
        $productCategory = $this->productCategoryRepository->findOneBy([
            'id' => $id,
        ]);

        if (!$productCategory) {
            throw new NotFoundException();
        }

        return $this->productCategoryRepository->delete($productCategory);
    }

    public function updateCategoryUrl(
        string $routeName,
        ?ProductCategory $category,
        array $selectedCategories,
        int $pageNumber = 1,
    ): string {
        $updatedCategories = $selectedCategories;

        if ($category) {
            $isActive = in_array($category->getSlug(), $selectedCategories, true);

            $updatedCategories = $isActive
                ? array_filter($selectedCategories, fn ($slug) => $slug !== $category->getSlug())
                : array_merge($selectedCategories, [$category->getSlug()]);
        }

        return $this->urlGenerator->generate($routeName, [
            'categories' => implode(',', $updatedCategories),
            'page' => $pageNumber,
        ]);
    }

    public function setCategoryUrl(string $routeName, ProductCategory $category): string
    {
        return $this->urlGenerator->generate($routeName, [
            'categories' => $category->getSlug(),
        ]);
    }

    public function getSlugsInArray(Request $request): array
    {
        $slugs = $request->query->get('categories', '');

        return $slugs ? explode(',', $slugs) : [];
    }

    public function getCategories(): array
    {
        try {
            return $this->cache->get('product_categories', function(ItemInterface $item) {
                $item->expiresAfter(3600);

                $this->logger->info('Fetching categories from the database.');

                return $this->productCategoryRepository->findAllWithTranslations();
            });
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Cache problem: ' . $e->getMessage());

            return $this->productCategoryRepository->findAllWithTranslations();
        }
    }
}
