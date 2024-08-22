<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Enum\LanguageEnum;
use Symfony\Component\Form\FormInterface;

class MultiLanguageHelper
{
    public function prepareMultipleNames(FormInterface $form): array
    {
        $names = [];
        foreach (LanguageEnum::cases() as $language) {
            $fieldName = 'name_' . $language->value;
            $name = $form->get($fieldName)->getData();
            if ($name) {
                $names[$language->value] = $name;
            }
        }

        return $names;
    }

    public function prepareMultipleDescriptions(FormInterface $form): array
    {
        $descriptions = [];
        foreach (LanguageEnum::cases() as $language) {
            $fieldName = 'description_' . $language->value;
            $name = $form->get($fieldName)->getData();
            if ($name) {
                $descriptions[$language->value] = $name;
            }
        }

        return $descriptions;
    }
}
