<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProductCategory;
use App\Exception\NotFoundException;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductCategoryService
{
    public function __construct(
        private readonly ProductCategoryRepository $productCategoryRepository,
        private readonly ProductRepository $productRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
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
            'id' => $id
        ]);

        if (null === $productCategory) {
            throw new NotFoundException();
        }

        return $this->productCategoryRepository->delete($productCategory);
    }

    public function updateCategoryUrl(string $routeName, ProductCategory $category, array $selectedCategories): string
    {
        $isActive = in_array($category->getSlug(), $selectedCategories);

        $updatedCategories = $isActive
            ? array_filter($selectedCategories, fn($slug) => $slug !== $category->getSlug())
            : array_merge($selectedCategories, [$category->getSlug()]);

        return $this->urlGenerator->generate($routeName, [
            'categories' => implode(',', $updatedCategories),
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

    public function getProductsByCategories(Request $request, array $slugsArray): array
    {
        if (!empty($slugsArray)) {
            $categories = $this->productCategoryRepository->findBy(['slug' => $slugsArray]);

            $products = $this->productRepository->findByCategories($categories);
        } else {
            $products = $this->productRepository->findAll();
        }

        return $products;
    }
}
