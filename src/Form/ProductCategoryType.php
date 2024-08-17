<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ProductCategory;
use App\Enum\LanguageEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('language', ChoiceType::class, [
            'label' => 'Język',
            'choices' => array_flip(LanguageEnum::getPolishNames()),
            'mapped' => false,
            'required' => true,
        ]);

        foreach (LanguageEnum::cases() as $language) {
            $builder->add('name_' . $language->value, TextType::class, [
                'label' => 'Nazwa (' . LanguageEnum::getPolishName($language) . ')',
                'mapped' => false,
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCategory::class,
        ]);
    }
}
