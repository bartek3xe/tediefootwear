<?php

declare(strict_types=1);

namespace App\Service\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Enum\FilterEnum;
use App\Service\Filter\Trait\CommonPropertiesTrait;
use App\Service\Filter\Trait\SortableTrait;
use Doctrine\ORM\QueryBuilder;

class ProductFilter extends AbstractFilter
{
    use CommonPropertiesTrait;
    use SortableTrait;

    public function getDescription(string $resourceClass): array
    {
        return [
            FilterEnum::SEARCH_PROPERTY->value => [
                'property' => FilterEnum::SEARCH_PROPERTY->value,
                'type' => 'string',
                'required' => false,
                'swagger' => ['description' => 'Search filter for products'],
            ],
        ];
    }

    protected function filterProperty(
        string $property,
        mixed $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        if (!$value) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $this->applyRelationFilters($queryBuilder, $property, $value, $alias);
        $this->handleSort($queryBuilder, $property, $value, $context, $alias);
    }

    protected function getFilterableProperties(): array
    {
        return [];
    }

    protected function getFilterableRelations(): array
    {
        return [];
    }

    protected function getSortFieldMapping(): array
    {
        return [];
    }
}
