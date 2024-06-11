<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/change-language', name: 'app_language_')]
class LanguageController extends AbstractController
{
    #[Route('/{locale}', name: 'changer', methods: ['GET'])]
    public function changeLanguage(Request $request, string $locale): Response
    {
        $availableLocales = ['pl', 'en', 'de', 'fr', 'es', 'it', 'ru', 'nl'];

        if (in_array($locale, $availableLocales, true)) {
            $request->getSession()->set('_locale', $locale);
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return new RedirectResponse($referer);
        }

        return $this->redirectToRoute('app');
    }
}
