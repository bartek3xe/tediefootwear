<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class ProductControllerService
{
    public function __construct(
        private readonly ProductService $service,
        private readonly UrlService $urlService,
    ) {
    }

    public function handleProductRequest(Request $request): array
    {
        $pageNumber = $this->urlService->getPageNumber($request);
        $selectedCategories = $this->urlService->getSelectedCategories($request);

        $paginatedProducts = $this->service->getProductsByCategories($selectedCategories, $pageNumber);

        if ($pageNumber > $paginatedProducts['total_pages']) {
            $pageNumber = $paginatedProducts['total_pages'];
        }

        $options = [
            'selectedCategories' => $selectedCategories,
            'page' => $pageNumber,
        ];

        return [
            'products' => $paginatedProducts['data'],
            'total_pages' => $paginatedProducts['total_pages'],
            'total_elements' => $paginatedProducts['total_elements'],
            'page_number' => $pageNumber,
            'start_page' => max(1, $pageNumber - 2),
            'end_page' => min($paginatedProducts['total_pages'], $pageNumber + 2),
            'options' => $options,
        ];
    }
}
