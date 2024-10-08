<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Entity\Product;
use App\Service\Factory\Translation\ProductTranslationFactory;
use App\Service\ProductService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

class ProductHandler extends AbstractTranslationHandler
{
    public function __construct(
        private readonly ProductService $service,
        ManagerRegistry $doctrine,
    ) {
        parent::__construct($doctrine);
    }

    public function handleForm(FormInterface $form, Product $product): void
    {
        $this->handleTranslations($form, $product, function($name, $description, $language) {
            return ProductTranslationFactory::create($name, $description, $language);
        });

        $this->service->save($product);
    }

    public function prepareData(FormInterface $form, Product $product): FormInterface
    {
        return $this->prepareFormData($form, $product);
    }
}
