<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 */
class ProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    public function save(ProductCategory $productCategory): bool
    {
        $em = $this->getEntityManager();

        $em->persist($productCategory);
        $em->flush();

        return true;
    }

    public function delete(ProductCategory $productCategory): bool
    {
        $em = $this->getEntityManager();

        $em->remove($productCategory);
        $em->flush();

        return true;
    }

    public function findBySearchQuery(string $query, string $locale): array
    {
        return $this->createQueryBuilder('pc')
            ->innerJoin('pc.translations', 't')
            ->where('LOWER(t.name) LIKE LOWER(:query)')
            ->andWhere('t.language = :locale')
            ->setParameter('locale', $locale)
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }


    public function findAllWithTranslations(): array
    {
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.translations', 't')
            ->addSelect('t')
            ->getQuery()
            ->getResult();
    }
}
