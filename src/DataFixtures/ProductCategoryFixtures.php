<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductCategoryFixtures extends Fixture
{
    public const array CATEGORIES = [
        [
            'pl' => 'Kapcie damskie',
            'en' => 'Women slippers',
        ],
        [
            'pl' => 'Kapcie męskie',
            'en' => 'Men slippers',
        ],
        [
            'pl' => 'Drewniaki damskie',
            'en' => 'Women clogs',
        ],
        [
            'pl' => 'Drewniaki męskie',
            'en' => 'Men clogs',
        ],
        [
            'pl' => 'Kapcie dziecięce',
            'en' => 'Children slippers',
        ],
        [
            'pl' => 'Specjalne oferty',
            'en' => 'Special offers',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $numberOfCategory = 1;
        foreach (self::CATEGORIES as $category) {
            $productCategory = new ProductCategory();
            $productCategory->setName($category);
            $manager->persist($productCategory);

            $this->addReference('category_' . $numberOfCategory, $productCategory);

            ++$numberOfCategory;
        }

        $manager->flush();
    }
}
