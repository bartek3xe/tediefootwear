<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Service\Builder\FieldBuilder;

class ListFieldService
{
    public function getFieldsForEntity(string $entityClass): array
    {
        $builder = new FieldBuilder();

        return match ($entityClass) {
            Product::class => $this->buildProductFields($builder),
            ProductCategory::class => $this->buildCategoryFields($builder),
            default => throw new \InvalidArgumentException("Unknown entity type: $entityClass"),
        };
    }

    private function buildProductFields(FieldBuilder $builder): array
    {
        return $builder
            ->addField('id', 'direct')
            ->addField('name', 'api', '/api/product_translations', ['product' => 'id', 'language' => 'pl'])
            ->addField('new', 'direct')
            ->addField('top', 'direct')
            ->addField('allegro_url', 'direct')
            ->addField('etsy_url', 'direct')
            ->build();
    }

    private function buildCategoryFields(FieldBuilder $builder): array
    {
        return $builder
            ->addField('id', 'direct')
            ->addField('name', 'api', '/api/product_category_translations', ['category' => 'id', 'language' => 'pl'])
            ->build();
    }
}
