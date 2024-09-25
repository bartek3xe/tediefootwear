<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUMBER_OF_PRODUCTS = 50;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($productNumber = 1; $productNumber <= self::NUMBER_OF_PRODUCTS; ++$productNumber) {
            $product = new Product();
            $product->setName([
                'pl' => 'Produkt ' . $productNumber . ': ' . $faker->word,
                'en' => 'Product ' . $productNumber . ': ' . $faker->word,
            ]);

            $product->setDescription([
                'pl' => $faker->sentence,
                'en' => $faker->sentence,
            ]);

            $product
                ->setIsNew($faker->boolean)
                ->setIsTop($faker->boolean)
                ->setAllegroUrl($faker->url)
                ->setEtsyUrl($faker->url)
            ;

            $productCategoryReference = $this->getReference(
                sprintf(
                    'category_%s',
                    random_int(1, count(ProductCategoryFixtures::CATEGORIES)),
                ),
            );

            if (!$productCategoryReference instanceof ProductCategory) {
                throw new \RuntimeException('Invalid apartment reference obtained');
            }

            $product->addCategory($productCategoryReference);
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ProductCategoryFixtures::class];
    }
}
