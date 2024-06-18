<?php

declare(strict_types=1);

namespace App\Twig;

use App\Enum\LanguageEnum;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageExtension extends AbstractExtension
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_locales', [$this, 'getAllLocales']),
            new TwigFunction('get_locale', [$this, 'getLocale']),
            new TwigFunction('get_locale_name', [$this, 'getLocaleName']),
            new TwigFunction('get_locale_flag', [$this, 'getLocaleFlag']),
        ];
    }

    public function getAllLocales(): array
    {
        return array_map(function (LanguageEnum $language) {
            return [
                'code' => $language->value,
                'name' => LanguageEnum::getNativeName($language),
                'flag' => LanguageEnum::getFlagCode($language),
            ];
        }, LanguageEnum::cases());
    }

    public function getLocale(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? $request->getLocale() : 'pl';
    }

    public function getLocaleName(string $locale): string
    {
        return LanguageEnum::getNativeName(LanguageEnum::from($locale));
    }

    public function getLocaleFlag(string $locale): string
    {
        return LanguageEnum::getFlagCode(LanguageEnum::from($locale));
    }
}
