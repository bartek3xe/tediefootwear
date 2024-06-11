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

    public function getNativeName(): string
    {
        return match($this) {
            self::POLISH => 'Polski',
            self::ENGLISH => 'English',
            self::GERMAN => 'Deutsch',
            self::FRENCH => 'Français',
            self::SPANISH => 'Español',
            self::ITALIAN => 'Italiano',
            self::UKRAINIAN => 'Українська',
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
}
