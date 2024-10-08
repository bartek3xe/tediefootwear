<?php

declare(strict_types=1);

namespace App\Service\Handler;

use App\Enum\LanguageEnum;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

abstract class AbstractTranslationHandler
{
    public function __construct(
        protected readonly ManagerRegistry $doctrine,
    ) {
    }

    protected function handleTranslations(FormInterface $form, object $entity, callable $translationFactory): void
    {
        foreach (LanguageEnum::cases() as $language) {
            $name = $form->get('name_' . $language->value)->getData();
            $description = $form->has('description_' . $language->value)
                ? $form->get('description_' . $language->value)->getData()
                : null;

            $translation = $entity->getTranslation($language->value);

            if ($name || $description) {
                if ($translation) {
                    $translation->setName($name);
                    if ($description !== null) {
                        $translation->setDescription($description);
                    }
                } else {
                    $translation = $translationFactory($name, $description, $language->value);
                    $entity->addTranslation($translation);
                }

                $em = $this->doctrine->getManager();
                $em->persist($translation);
            }
        }
    }

    protected function prepareFormData(FormInterface $form, object $entity): FormInterface
    {
        foreach (LanguageEnum::cases() as $language) {
            $translation = $entity->getTranslation($language->value);
            $form->get('name_' . $language->value)
                ->setData($translation ? $translation->getName() : '');

            if ($form->has('description_' . $language->value)) {
                $form->get('description_' . $language->value)
                    ->setData($translation ? $translation->getDescription() : '');
            }
        }

        return $form;
    }
}
