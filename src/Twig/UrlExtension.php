<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\UrlService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UrlExtension extends AbstractExtension
{
    public function __construct(private readonly UrlService $service)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generate_url', [$this->service, 'generateUrl']),
        ];
    }
}
