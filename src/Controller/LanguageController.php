<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\LanguageEnum;

class LanguageController extends AbstractController
{
    #[Route('/change-language/{locale}', name: 'app_language_changer', methods: ['GET'])]
    public function changeLanguage(Request $request, string $locale): Response
    {
        $availableLocales = array_map(fn(LanguageEnum $lang) => $lang->value, LanguageEnum::cases());

        if (in_array($locale, $availableLocales, true)) {
            $request->getSession()->set('_locale', $locale);
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            $parsedUrl = parse_url($referer);
            $pathSegments = explode('/', $parsedUrl['path']);

            if (in_array($pathSegments[1], $availableLocales)) {
                $pathSegments[1] = $locale;
            } else {
                array_unshift($pathSegments, $locale);
            }

            $newPath = implode('/', $pathSegments);
            $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $newReferer = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $port . $newPath;

            if (isset($parsedUrl['query'])) {
                $newReferer .= '?' . $parsedUrl['query'];
            }

            return new RedirectResponse($newReferer);
        }

        return $this->redirectToRoute('app', ['_locale' => $locale]);
    }

    #[Route('/', name: 'app_default_redirect')]
    public function redirectToDefaultLanguage(Request $request): Response
    {
        $locale = $request->getSession()->get('_locale', 'pl');
        return $this->redirectToRoute('app', ['_locale' => $locale]);
    }
}
