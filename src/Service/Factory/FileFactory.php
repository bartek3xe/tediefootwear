<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\Entity\File;
use App\Entity\Product;

class FileFactory
{
    public static function create(
        string $filename,
        string $filepath,
        string $originalFilename,
        int $fileSize,
        ?string $extension,
        int $position,
        ?Product $product = null,
    ): File {
        return (new File())
            ->setFilename($filename)
            ->setFilepath($filepath)
            ->setOriginalFilename($originalFilename)
            ->setSize($fileSize)
            ->setUploadedAt(new \DateTimeImmutable())
            ->setExtension($extension)
            ->setProduct($product)
            ->setPosition($position)
        ;
    }
}
