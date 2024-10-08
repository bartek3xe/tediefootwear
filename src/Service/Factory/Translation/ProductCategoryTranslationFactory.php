<?php

declare(strict_types=1);

namespace App\Service\Factory\Translation;

use App\Entity\ProductCategory;
use App\Entity\Translation\ProductCategoryTranslation;

class ProductCategoryTranslationFactory
{
    public static function create(
        ?string $name,
        string $language,
        ?ProductCategory $productCategory = null,
    ): ProductCategoryTranslation {
        return (new ProductCategoryTranslation())
            ->setName($name)
            ->setLanguage($language)
            ->setCategory($productCategory)
        ;
    }
}
