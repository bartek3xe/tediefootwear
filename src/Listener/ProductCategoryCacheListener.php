<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\ProductCategory;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ProductCategoryCacheListener
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->invalidateCategoryCache($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->invalidateCategoryCache($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->invalidateCategoryCache($args);
    }

    private function invalidateCategoryCache(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof ProductCategory) {
            return;
        }

        try {
            $this->cache->delete('category_' . $entity->getId());
            $this->logger->info('Cache for category invalidated for ID ' . $entity->getId());
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Error during cache invalidation for category: ' . $e->getMessage());
        }
    }
}
