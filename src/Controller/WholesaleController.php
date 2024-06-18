<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class WholesaleController extends AbstractController
{
    #[Route('/wholesale', name: 'wholesale')]
    public function index(Request $request): Response
    {
        return $this->render('wholesale/index.html.twig');
    }
}
