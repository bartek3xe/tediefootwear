<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlService
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function generateUrl(string $routeName, array $options = []): string
    {
        $this->setCategories($options);
        $urlParams = array_filter($options, fn ($value) => !empty($value));

        return $this->urlGenerator->generate($routeName, $urlParams);
    }

    public function getPageNumber(Request $request): int
    {
        $pageNumber = $request->query->get('page', 1);

        return filter_var($pageNumber, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
    }

    public function getSelectedCategories(Request $request): array
    {
        $selectedCategories = (array) $request->query->get('categories') ?: [];

        return array_filter($selectedCategories, fn ($value) => !empty($value));
    }

    private function setCategories(array &$options): void
    {
        $selectedCategories = $options['selectedCategories'] ?? [];

        if (isset($options['category_slug'])) {
            $categorySlug = $options['category_slug'];

            $isActive = in_array($categorySlug, $selectedCategories, true);
            $selectedCategories = $isActive
                ? array_filter($selectedCategories, fn ($slug) => $slug !== $categorySlug)
                : array_merge($selectedCategories, [$categorySlug]);

            $options['selectedCategories'] = implode(',', $selectedCategories);
        }

        $options['categories'] = implode(',', $selectedCategories);
        unset($options['selectedCategories'], $options['category_slug']);
    }
}
