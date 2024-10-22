<?php

declare(strict_types=1);

namespace App\Service\Filter\Trait;

use Doctrine\ORM\QueryBuilder;

trait CommonPropertiesTrait
{
    protected function applyCommonPropertiesFilter(
        QueryBuilder $queryBuilder,
        string $property,
        mixed $value,
        string $alias,
    ): void {
        $properties = $this->getFilterableProperties();

        foreach ($properties as $field) {
            if ($property === $field && !is_array($value)) {
                $queryBuilder
                    ->andWhere(sprintf('%s.%s LIKE :%s_param', $alias, $field, $field))
                    ->setParameter($field . '_param', '%' . $value . '%');
                break;
            }
            if ($property === $field && is_array($value)) {
                if (count($value) > 0) {
                    $queryBuilder
                        ->andWhere(sprintf('%s.%s IN (:%s_param)', $alias, $field, $field))
                        ->setParameter($field . '_param', $value);
                }
                break;
            }
        }
    }

    protected function applyRelationFilters(
        QueryBuilder $queryBuilder,
        string $property,
        mixed $value,
        string $alias,
    ): void {
        $relations = $this->getFilterableRelations();

        foreach ($relations as $field => $param) {
            if ($property === $param) {
                if (is_array($value)) {
                    if (!empty($value)) {
                        $queryBuilder
                            ->andWhere(sprintf('%s.%s IN (:%s_param)', $alias, $field, $param))
                            ->setParameter($param . '_param', $value);
                    }
                    break;
                }
                if (is_string($value)) {
                    $queryBuilder
                        ->andWhere(sprintf('%s.%s = :%s_param', $alias, $field, $param))
                        ->setParameter($param . '_param', $value);
                }
            }
        }
    }

    abstract protected function getFilterableProperties(): array;

    abstract protected function getFilterableRelations(): array;
}
