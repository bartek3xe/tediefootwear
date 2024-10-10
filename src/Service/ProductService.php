<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Exception\NotFoundException;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository,
        private readonly ProductCategoryRepository $categoryRepository,
    ) {
    }

    public function save(Product $product): bool
    {
        return $this->repository->save($product);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(int $id): bool
    {
        $product = $this->repository->findOneBy([
            'id' => $id,
        ]);

        if (!$product) {
            throw new NotFoundException();
        }

        return $this->repository->delete($product);
    }

    public function getProductsByCategories(array $slugsArray, int $pageNumber): array
    {
        if (!empty($slugsArray)) {
            $categories = $this->categoryRepository->findBy(['slug' => $slugsArray]);
            $products = $this->repository->findByCategoriesPaginated($categories, $pageNumber);
        } else {
            $products = $this->repository->findAllPaginated($pageNumber);
        }

        return $products;
    }
}
