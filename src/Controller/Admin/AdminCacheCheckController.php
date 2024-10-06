<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route(name: 'app_admin_')]
class AdminCacheCheckController extends AbstractController
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    #[Route('/cache-check', name: 'cache_check')]
    public function checkCache(): Response
    {
        $categories = $this->cache->getItem('product_categories');
        if ($categories->isHit()) {
            return new Response('Cache hit: ' . json_encode($categories->get()));
        }

        return new Response('Cache miss');
    }
}

