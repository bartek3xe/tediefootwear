<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class AdminDashboardController extends AbstractController
{
    #[Route('/secret-admin', name: 'admin_dashboard')]
    public function index(Request $request): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
