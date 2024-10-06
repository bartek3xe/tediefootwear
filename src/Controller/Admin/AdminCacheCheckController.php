<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route(name: 'app_admin_')]
class AdminCacheCheckController extends AbstractController
{
    public function __construct(private readonly CacheInterface $cache)
    {
    }

    #[Route('/cache-check', name: 'cache_check')]
    public function checkCache(): Response
    {
        try {
            $categories = $this->cache->get('product_categories', function(ItemInterface $item) {
                $item->expiresAfter(3600);

                return new Response('Cache miss');
            });
        } catch (InvalidArgumentException $e) {
            return new Response('Cache miss');
        }

        return new Response('Cache hit: ' . $categories);
    }
}
