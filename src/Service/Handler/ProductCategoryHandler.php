<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Entity\ProductCategory;
use App\Service\Factory\Translation\ProductCategoryTranslationFactory;
use App\Service\ProductCategoryService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

class ProductCategoryHandler extends AbstractTranslationHandler
{
    public function __construct(
        private readonly ProductCategoryService $service,
        ManagerRegistry $doctrine
    ) {
        parent::__construct($doctrine);
    }

    public function handleForm(FormInterface $form, ProductCategory $productCategory): void
    {
        $this->handleTranslations($form, $productCategory, function ($name, $description, $language) {
            return ProductCategoryTranslationFactory::create($name, $language);
        });

        $this->service->save($productCategory);
    }

    public function prepareData(FormInterface $form, ProductCategory $productCategory): FormInterface
    {
        return $this->prepareFormData($form, $productCategory);
    }
}
