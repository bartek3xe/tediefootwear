<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<File>
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function save(File $file): bool
    {
        $em = $this->getEntityManager();
        $em->persist($file);
        $em->flush();

        return true;
    }

    public function countFilesByProduct(Product $product): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->where('f.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
