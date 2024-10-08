<?php

declare(strict_types=1);

namespace App\Service\Helper;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationHelper
{
    public function paginate(QueryBuilder $queryBuilder, int $pageNumber, int $perPage): array
    {
        $totalElements = $this->calcTotalElements($queryBuilder);
        $totalPages = $this->calcTotalPages($totalElements, $perPage);

        if ($pageNumber > $totalPages && 0 !== $totalPages) {
            $pageNumber = $totalPages;
        }

        if ($pageNumber < 1) {
            $pageNumber = 1;
        }

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        $paginator = $this->paginateQuery($queryBuilder, $perPage, $pageNumber);

        return [
            'data' => iterator_to_array($paginator),
            'total_elements' => $totalElements,
            'total_pages' => $totalPages,
        ];
    }

    public function paginateQuery(
        QueryBuilder $queryBuilder,
        int $perPage,
        int $pageNumber = 1,
    ): Paginator {
        $paginator = new Paginator($queryBuilder);

        $paginator
            ->getQuery()
            ->setFirstResult(($pageNumber - 1) * $perPage)
            ->setMaxResults($perPage);

        return $paginator;
    }

    public function calcTotalElements(QueryBuilder $queryBuilder): int
    {
        $qb = clone $queryBuilder;
        $qb->select('COUNT(p.id)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function calcTotalPages(int $totalElements, int $perPage): int
    {
        return (int) ceil($totalElements / $perPage);
    }
}
