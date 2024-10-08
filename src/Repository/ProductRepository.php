<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Service\Helper\PaginationHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private const int DEFAULT_MAX_ELEMENTS_PER_PAGE = 8;

    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginationHelper $paginationHelper,
    ) {
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

    public function findAllPaginated(
        int $pageNumber,
        int $perPage = self::DEFAULT_MAX_ELEMENTS_PER_PAGE,
    ): array {
        $queryBuilder = $this->createQueryBuilder('p');

        return $this->paginationHelper->paginate($queryBuilder, $pageNumber, $perPage);
    }

    public function findByCategoriesPaginated(
        array $categories,
        int $pageNumber,
        int $perPage = self::DEFAULT_MAX_ELEMENTS_PER_PAGE,
    ): array {
        $queryBuilder = $this->includeByCategories($categories, $this->createQueryBuilder('p'));

        return $this->paginationHelper->paginate($queryBuilder, $pageNumber, $perPage);
    }

    public function findByCategories(array $categories): array
    {
        $queryBuilder = $this->includeByCategories($categories, $this->createQueryBuilder('p'));

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearchQuery(string $query, string $locale): array
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(JSON_UNQUOTE(JSON_EXTRACT(p.name, :locale))) LIKE LOWER(:query)')
            ->setParameter('locale', '$.' . $locale)
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

    private function includeByCategories(array $categories, QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin(':alias.categories', 'c')
            ->where('c.id IN (:categories)')
            ->setParameter('categories', $categories)
            ->setParameter('alias', $queryBuilder->getRootAliases()[0])
        ;
    }
}
