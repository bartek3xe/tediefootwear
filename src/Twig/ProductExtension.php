<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_first_image', [$this, 'getFirstImage']),
            new TwigFunction('sort_by_position', [$this, 'sortByPosition']),
        ];
    }

    /**
    * @param Collection<File> $files
    */
    public function getFirstImage(Collection $files): ?File
    {
        if ($files->isEmpty()) {
            return null;
        }

        $firstImage = $files->first();
        foreach ($files as $file) {
            if ($file->getPosition() < $firstImage->getPosition()) {
                $firstImage = $file;
            }
        }

        return $firstImage;
    }

    /**
     * @param Collection<File> $files
     */
    public function sortByPosition(Collection $files): ArrayCollection
    {
        $filesArray = $files->toArray();

        usort($filesArray, function (File $a, File $b) {
            return $a->getPosition() <=> $b->getPosition();
        });

        return new ArrayCollection($filesArray);
    }
}
