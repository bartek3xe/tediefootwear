<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProductCategory;
use App\Exception\NotFoundException;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductCategoryService
{
    public function __construct(
        private readonly ProductCategoryRepository $repository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function save(ProductCategory $productCategory): bool
    {
        return $this->repository->save($productCategory);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(int $id): bool
    {
        $productCategory = $this->repository->findOneBy([
            'id' => $id
        ]);

        if (null === $productCategory) {
            throw new NotFoundException();
        }

        return $this->repository->delete($productCategory);
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
}
