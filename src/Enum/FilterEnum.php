<?php

declare(strict_types=1);

namespace App\Enum;

enum FilterEnum: string
{
    case SEARCH_PROPERTY = 'search';
    case PRODUCT_PROPERTY = 'product';
    case CATEGORY_PROPERTY = 'category';
    case LANGUAGE_PROPERTY = 'language';
}
