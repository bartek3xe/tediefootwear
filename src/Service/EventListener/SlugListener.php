<?php

declare(strict_types=1);

namespace App\Service\EventListener;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugListener
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductCategory || $entity instanceof Product) {
            $this->generateSlug($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductCategory || $entity instanceof Product) {
            $this->generateSlug($entity);
        }
    }

    private function generateSlug(ProductCategory|Product $entity): void
    {
        $entity->setSlug((string) $this->slugger->slug($entity->getTranslation('en')->getName())->lower());
    }
}
