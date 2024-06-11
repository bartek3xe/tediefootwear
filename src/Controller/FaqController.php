<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class FaqController extends AbstractController
{
    #[Route('/faq', name: 'faq')]
    public function index(Request $request): Response
    {
        return $this->render('faq/index.html.twig');
    }
}
