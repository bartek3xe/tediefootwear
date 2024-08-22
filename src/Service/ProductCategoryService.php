<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProductCategory;
use App\Exception\NotFoundException;
use App\Repository\ProductCategoryRepository;

class ProductCategoryService
{
    public function __construct(private readonly ProductCategoryRepository $repository)
    {
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
}
