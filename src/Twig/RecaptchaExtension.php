<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\LanguageService;
use App\Service\RecaptchaService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RecaptchaExtension extends AbstractExtension
{
    public function __construct(private readonly RecaptchaService $recaptchaService)
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_recaptcha_key', [$this->recaptchaService, 'getRecaptchaKey']),
        ];
    }
}
