<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\LanguageService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageExtension extends AbstractExtension
{
    public function __construct(private readonly LanguageService $languageService)
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_locales', [$this->languageService, 'getAllLocales']),
            new TwigFunction('get_locale', [$this->languageService, 'getLocale']),
            new TwigFunction('get_default_locale', [$this->languageService, 'getDefaultLocale']),
            new TwigFunction('get_locale_name', [$this->languageService, 'getLocaleName']),
            new TwigFunction('get_locale_flag', [$this->languageService, 'getLocaleFlag']),
        ];
    }
}
