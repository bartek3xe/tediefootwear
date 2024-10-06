<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\ProductCategoryRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

class TwigGlobalCategorySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProductCategoryRepository $categoryRepository,
        private readonly Environment $twig,
        private readonly CacheInterface $cache,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        try {
            $categories = $this->cache->get('product_categories', function (ItemInterface $item) {
                $item->expiresAfter(3600);
                return $this->categoryRepository->findAll();
            });
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Cache problem: ' . $e->getMessage());
            return;
        }

        $this->twig->addGlobal('product_categories', $categories);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
