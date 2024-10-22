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

class ProductCategoryTranslationFilter extends AbstractFilter
{
    use CommonPropertiesTrait;
    use SortableTrait;

    public function getDescription(string $resourceClass): array
    {
        return [
            FilterEnum::CATEGORY_PROPERTY->value => [
                'property' => FilterEnum::CATEGORY_PROPERTY->value,
                'type' => 'string',
                'required' => false,
                'swagger' => ['description' => 'Search category translation by product'],
            ],
            FilterEnum::LANGUAGE_PROPERTY->value => [
                'property' => FilterEnum::LANGUAGE_PROPERTY->value,
                'type' => 'string',
                'required' => false,
                'swagger' => ['description' => 'Search category translation by language'],
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
        $this->applyCommonPropertiesFilter($queryBuilder, $property, $value, $alias);
        $this->handleSort($queryBuilder, $property, $value, $context, $alias);
    }

    protected function getFilterableProperties(): array
    {
        return [
            FilterEnum::LANGUAGE_PROPERTY->value => FilterEnum::LANGUAGE_PROPERTY->value,
        ];
    }

    protected function getFilterableRelations(): array
    {
        return [
            FilterEnum::CATEGORY_PROPERTY->value => FilterEnum::CATEGORY_PROPERTY->value,
        ];
    }

    protected function getSortFieldMapping(): array
    {
        return [];
    }
}
