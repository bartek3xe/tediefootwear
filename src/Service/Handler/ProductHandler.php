<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Entity\Product;
use App\Enum\LanguageEnum;
use App\Service\ProductService;
use Symfony\Component\Form\FormInterface;

class ProductHandler
{
    public function __construct(
        private readonly ProductService $service,
        private readonly MultiLanguageHelper $multiLanguageHelper,
    ) {
    }

    public function handleForm(FormInterface $form, Product $product): void
    {
        $names = $this->multiLanguageHelper->prepareMultipleNames($form);
        $descriptions = $this->multiLanguageHelper->prepareMultipleDescriptions($form);

        $product->setName($names);
        $product->setDescription($descriptions);

        $this->service->save($product);
    }

    public function prepareData(FormInterface $form, Product $product): FormInterface
    {
        foreach (LanguageEnum::cases() as $language) {
            $form->get('name_' . $language->value)
                ->setData($product->getName()[$language->value] ?? '');
            $form->get('description_' . $language->value)
                ->setData($product->getDescription()[$language->value] ?? '');
        }

        return $form;
    }
}
