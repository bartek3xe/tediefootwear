<?php

namespace App\Enum;

enum LanguageEnum: string
{
    case POLISH = 'pl';
    case ENGLISH = 'en';
    case GERMAN = 'de';
    case FRENCH = 'fr';
    case SPANISH = 'es';
    case ITALIAN = 'it';
    case UKRAINIAN = 'uk';
    case SWEDISH = 'sv';
    case FINNISH = 'fi';
    case NORWEGIAN = 'no';
    case DUTCH = 'nl';
    case LITHUANIAN = 'lt';
    case DANISH = 'da';
    case ESTONIAN = 'et';
    case CZECH = 'cs';
    case SLOVAK = 'sk';
    case LATVIAN = 'lv';
    case ROMANIAN = 'ro';
    case CROATIAN = 'hr';
    case HUNGARIAN = 'hu';
    case BULGARIAN = 'bg';
    case SERBIAN = 'sr';
    case SLOVENIAN = 'sl';
    case RUSSIAN = 'ru';
    case GREEK = 'el';

    public static function getNativeName(LanguageEnum $enum): string
    {
        return match($enum) {
            self::POLISH => 'Polski',
            self::ENGLISH => 'English',
            self::GERMAN => 'Deutsch',
            self::FRENCH => 'Français',
            self::SPANISH => 'Español',
            self::ITALIAN => 'Italiano',
            self::UKRAINIAN => 'Українська',
            self::SWEDISH => 'Svenska',
            self::FINNISH => 'Suomi',
            self::NORWEGIAN => 'Norsk',
            self::DUTCH => 'Nederlands',
            self::LITHUANIAN => 'Lietuvių',
            self::DANISH => 'Dansk',
            self::ESTONIAN => 'Eesti',
            self::CZECH => 'Čeština',
            self::SLOVAK => 'Slovenčina',
            self::LATVIAN => 'Latviešu',
            self::ROMANIAN => 'Română',
            self::CROATIAN => 'Hrvatski',
            self::HUNGARIAN => 'Magyar',
            self::BULGARIAN => 'Български',
            self::SERBIAN => 'Српски',
            self::SLOVENIAN => 'Slovenščina',
            self::RUSSIAN => 'Русский',
            self::GREEK => 'Ελληνικά',
        };
    }

    public static function getFlagCode(LanguageEnum $enum): string
    {
        return match($enum) {
            self::POLISH => 'pl',
            self::ENGLISH => 'gb',
            self::GERMAN => 'de',
            self::FRENCH => 'fr',
            self::SPANISH => 'es',
            self::ITALIAN => 'it',
            self::UKRAINIAN => 'ua',
            self::SWEDISH => 'se',
            self::FINNISH => 'fi',
            self::NORWEGIAN => 'no',
            self::DUTCH => 'nl',
            self::LITHUANIAN => 'lt',
            self::DANISH => 'dk',
            self::ESTONIAN => 'ee',
            self::CZECH => 'cz',
            self::SLOVAK => 'sk',
            self::LATVIAN => 'lv',
            self::ROMANIAN => 'ro',
            self::CROATIAN => 'hr',
            self::HUNGARIAN => 'hu',
            self::BULGARIAN => 'bg',
            self::SERBIAN => 'rs',
            self::SLOVENIAN => 'si',
            self::RUSSIAN => 'ru',
            self::GREEK => 'gr',
        };
    }
}
