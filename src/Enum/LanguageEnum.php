<?php

declare(strict_types=1);

namespace App\Enum;

enum LanguageEnum: string
{
    public const string DEFAULT_LOCALE_VALUE = self::ENGLISH->value;

    case POLISH = 'pl';
    case ENGLISH = 'en';
    case GERMAN = 'de';
    case FRENCH = 'fr';
    case SPANISH = 'es';
    case UKRAINIAN = 'uk';

    public static function getNativeName(LanguageEnum $enum): string
    {
        return match ($enum) {
            self::POLISH => 'Polski',
            self::ENGLISH => 'English',
            self::GERMAN => 'Deutsch',
            self::FRENCH => 'Français',
            self::SPANISH => 'Español',
            self::UKRAINIAN => 'Українська',
        };
    }

    public static function getFlagCode(LanguageEnum $enum): string
    {
        return match ($enum) {
            self::POLISH => 'pl',
            self::ENGLISH => 'gb',
            self::GERMAN => 'de',
            self::FRENCH => 'fr',
            self::SPANISH => 'es',
            self::UKRAINIAN => 'ua',
        };
    }

    public static function getPolishName(LanguageEnum $enum): string
    {
        return match ($enum) {
            self::POLISH => 'Polski',
            self::ENGLISH => 'Angielski',
            self::GERMAN => 'Niemiecki',
            self::FRENCH => 'Francuski',
            self::SPANISH => 'Hiszpański',
            self::UKRAINIAN => 'Ukraiński',
        };
    }

    public static function getPolishNames(): array
    {
        $names = [];
        foreach (self::cases() as $enum) {
            $names[$enum->value] = self::getPolishName($enum);
        }

        return $names;
    }

    public static function getNativeNames(): array
    {
        $names = [];
        foreach (self::cases() as $enum) {
            $names[$enum->value] = self::getNativeName($enum);
        }

        return $names;
    }
}
