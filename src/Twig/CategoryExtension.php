<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\ProductCategoryService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('update_category_url', [$this->categoryService, 'updateCategoryUrl']),
            new TwigFunction('set_category_url', [$this->categoryService, 'setCategoryUrl']),
        ];
    }
}
