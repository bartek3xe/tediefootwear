<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    public function __construct(private readonly LanguageService $languageService)
    {
    }

    #[Route('/change-language/{locale}', name: 'app_language_changer', methods: ['GET'])]
    public function changeLanguage(Request $request, string $locale): Response
    {
        $availableLocales = $this->languageService->getAvailableLocales();

        $this->languageService->changeLocale($request, $locale, $availableLocales);

        $referer = $request->headers->get('referer');
        if ($referer) {
            $newReferer = $this->languageService->generateNewReferer($referer, $locale, $availableLocales);

            return new RedirectResponse($newReferer);
        }

        return $this->redirectToRoute('app', ['_locale' => $locale]);
    }

    #[Route('/', name: 'app_default_redirect')]
    public function redirectToDefaultLanguage(Request $request): Response
    {
        $locale = $this->languageService->getLocaleFromSession($request->getSession());

        return $this->redirectToRoute('app', ['_locale' => $locale]);
    }
}
