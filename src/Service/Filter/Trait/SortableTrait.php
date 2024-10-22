<?php

declare(strict_types=1);

namespace App\Service\Filter\Trait;

use Doctrine\ORM\QueryBuilder;

trait SortableTrait
{
    public function handleSort(
        QueryBuilder $queryBuilder,
        string $property,
        mixed $value,
        array $context,
        string $alias
    ): void {
        if ('sort' === $property) {
            $sortDirection = $context['filters']['direction'] ?? 'asc';
            $this->applySorting($queryBuilder, $value, $sortDirection, $alias);
        }
    }

    private function applySorting(
        QueryBuilder $queryBuilder,
        mixed $sortField,
        string $sortDirection,
        string $alias
    ): void {
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $sortFieldMapping = $this->getSortFieldMapping();
        $fields = $sortFieldMapping[$sortField] ?? null;

        if ('address' === $sortField) {
            $this->applyAddressSorting($queryBuilder, $sortDirection, $alias);
        }

        if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->addOrderByWithRelation($queryBuilder, $field, $sortDirection, $alias);
            }
        } else {
            $queryBuilder->addOrderBy(sprintf('%s.%s', $alias, $this->toCamelCase($sortField)), $sortDirection);
        }
    }

    private function addOrderByWithRelation(
        QueryBuilder $queryBuilder,
        string $field,
        string $sortDirection,
        string $alias,
    ): void {
        if (str_contains($field, '.')) {
            [$relation, $property] = explode('.', $field, 2);
            $relationAlias = $this->ensureRelationJoined($queryBuilder, $relation, $alias);
            $queryBuilder->addOrderBy(sprintf('%s.%s', $relationAlias, $property), $sortDirection);
        } else {
            $queryBuilder->addOrderBy(sprintf('%s.%s', $alias, $field), $sortDirection);
        }
    }

    private function ensureRelationJoined(QueryBuilder $queryBuilder, string $relation, string $alias): string
    {
        /** @var literal-string $relationAlias */
        $relationAlias = $alias . '_' . $relation;
        if (!in_array($relationAlias, $queryBuilder->getAllAliases())) {
            /** @var literal-string $statement */
            $statement = sprintf('%s.%s', $alias, $relation);
            $queryBuilder->leftJoin($statement, $relationAlias);
        }
        return $relationAlias;
    }

    private function applyAddressSorting(QueryBuilder $queryBuilder, string $sortDirection, string $alias): void
    {
        $addressAlias = $this->ensureRelationJoined($queryBuilder, 'address', $alias);

        $fields = ['street', 'buildingNumber', 'apartmentNumber', 'zipCode', 'town', 'voivodeship'];
        foreach ($fields as $field) {
            $queryBuilder->addOrderBy(sprintf('%s.%s', $addressAlias, $field), $sortDirection);
        }
    }

    private function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    abstract protected function getSortFieldMapping(): array;
}
