<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\LanguageEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LanguageService
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getDefaultLocale(): string
    {
        return LanguageEnum::DEFAULT_LOCALE_VALUE;
    }

    public function getLocale(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? $request->getLocale() : LanguageEnum::DEFAULT_LOCALE_VALUE;
    }

    public function getLocaleName(string $locale): string
    {
        return LanguageEnum::getNativeName(LanguageEnum::from($locale));
    }

    public function getLocaleFlag(string $locale): string
    {
        return LanguageEnum::getFlagCode(LanguageEnum::from($locale));
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

    public function getAvailableLocales(): array
    {
        return array_map(fn (LanguageEnum $lang) => $lang->value, LanguageEnum::cases());
    }

    public function changeLocale(Request $request, string $locale, array $availableLocales): void
    {
        if (in_array($locale, $availableLocales, true)) {
            $request->getSession()->set('_locale', $locale);
        }
    }

    public function getLocaleFromSession(SessionInterface $session): string
    {
        return $session->get('_locale', LanguageEnum::DEFAULT_LOCALE_VALUE);
    }

    public function generateNewReferer(string $referer, string $locale, array $availableLocales): string
    {
        $parsedUrl = parse_url($referer);
        $pathSegments = explode('/', $parsedUrl['path'] ?? '');

        if (isset($pathSegments[1]) && in_array($pathSegments[1], $availableLocales, true)) {
            $pathSegments[1] = $locale;
        } else {
            array_unshift($pathSegments, $locale);
        }

        $newPath = implode('/', $pathSegments);
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $newReferer = sprintf(
            '%s://%s%s%s',
            $parsedUrl['scheme'],
            $parsedUrl['host'],
            $port,
            $newPath,
        );

        if (isset($parsedUrl['query'])) {
            $newReferer .= '?' . $parsedUrl['query'];
        }

        return $newReferer;
    }
}
