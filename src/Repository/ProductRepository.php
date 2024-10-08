<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
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

    public function findByCategories(array $categories): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->where('c.id IN (:categories)')
            ->setParameter('categories', $categories)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearchQuery(string $query, string $locale): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.translations', 't')
            ->where('LOWER(t.name) LIKE LOWER(:query)')
            ->andWhere('t.language = :locale')
            ->setParameter('locale', $locale)
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

    public function countProductsByCategory(ProductCategory $productCategory): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->join('p.categories', 'c')
            ->where('c = :category')
            ->setParameter('category', $productCategory)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
