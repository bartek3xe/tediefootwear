<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use App\Service\Factory\Translation\ProductCategoryTranslationFactory;
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

    public function __construct(
        private readonly ProductCategoryTranslationFactory $productCategoryTranslationFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $numberOfCategory = 1;

        foreach (self::CATEGORIES as $category) {
            $productCategory = new ProductCategory();

            $categoryTranslationPl = $this->productCategoryTranslationFactory->create(
                $category['pl'],
                'pl',
            );

            $categoryTranslationEn = $this->productCategoryTranslationFactory->create(
                $category['en'],
                'en',
            );

            $productCategory->addTranslation($categoryTranslationPl);
            $productCategory->addTranslation($categoryTranslationEn);

            $manager->persist($productCategory);

            $this->addReference('category_' . $numberOfCategory, $productCategory);

            ++$numberOfCategory;
        }

        $manager->flush();
    }
}
