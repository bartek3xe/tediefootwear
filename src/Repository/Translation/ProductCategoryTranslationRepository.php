<?php

declare(strict_types=1);

namespace App\Repository\Translation;

use App\Entity\Translation\ProductCategoryTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategoryTranslation>
 */
class ProductCategoryTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategoryTranslation::class);
    }
}
