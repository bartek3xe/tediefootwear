<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\LanguageEnum;
use Symfony\Component\HttpFoundation\RequestStack;

class LanguageService
{
    private const DEFAULT_LOCALE = 'en';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getDefaultLocale(): string
    {
        return self::DEFAULT_LOCALE;
    }

    public function getAllLocales(): array
    {
        return array_map(function(LanguageEnum $language) {
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

        return $request ? $request->getLocale() : 'en';
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
