<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): bool
    {
        $em = $this->getEntityManager();

        $em->persist($product);
        $em->flush();

        return true;
    }

    public function delete(Product $product): bool
    {
        $em = $this->getEntityManager();

        $em->remove($product);
        $em->flush();

        return true;
    }
}
