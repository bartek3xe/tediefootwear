<?php

declare(strict_types=1);

namespace App\Service\Factory\Translation;

use App\Entity\Product;
use App\Entity\Translation\ProductTranslation;

class ProductTranslationFactory
{
    public static function create(
        ?string $name,
        ?string $description,
        string $language,
        ?Product $product = null,
    ): ProductTranslation {
        return (new ProductTranslation())
            ->setName($name)
            ->setDescription($description)
            ->setLanguage($language)
            ->setProduct($product)
        ;
    }
}
