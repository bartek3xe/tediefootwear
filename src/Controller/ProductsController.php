<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(Request $request): Response
    {
        return $this->render('products/index.html.twig');
    }
}
