<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Entity\ProductCategory;
use App\Enum\LanguageEnum;
use App\Service\ProductCategoryService;
use Symfony\Component\Form\FormInterface;

class ProductCategoryHandler
{
    public function __construct(
        private readonly ProductCategoryService $service,
        private readonly MultiLanguageHelper $multiLanguageHelper,
    ) {
    }

    public function handleForm(FormInterface $form, ProductCategory $productCategory): void
    {
        $names = $this->multiLanguageHelper->prepareMultipleNames($form);

        $productCategory->setName($names);
        $this->service->save($productCategory);
    }

    public function prepareData(FormInterface $form, ProductCategory $productCategory): FormInterface
    {
        foreach (LanguageEnum::cases() as $language) {
            $form->get('name_' . $language->value)
                ->setData($productCategory->getName()[$language->value] ?? '');
        }

        return $form;
    }
}
