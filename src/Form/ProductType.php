<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Enum\LanguageEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
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
            $constraints = [];
            if (in_array($language, [LanguageEnum::POLISH, LanguageEnum::ENGLISH])) {
                $constraints = [new NotBlank()];
            }

            $builder->add('name_' . $language->value, TextType::class, [
                'label' => 'Nazwa (' . LanguageEnum::getPolishName($language) . ')',
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints,
            ]);

            $builder->add('description_' . $language->value, TextareaType::class, [
                'label' => 'Opis (' . LanguageEnum::getPolishName($language) . ')',
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints,
            ]);
        }

        if ($options['edition']) {
            $builder->add('images', FileType::class, [
                'label' => 'Zdjęcia',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*'
                ],
            ]);
        }

        $builder
            ->add('category', EntityType::class, [
                'label' => 'Kategoria',
                'class' => ProductCategory::class,
                'choice_label' => function(ProductCategory $category) {
                    return $category->getNameByLanguage('en');
                },
                'placeholder' => 'Wybierz kategorię',
                'required' => true,
            ])
            ->add('is_new', CheckboxType::class, [
                'label' => 'Czy jest nowym produktem?',
                'required' => false,
            ])
            ->add('is_top', CheckboxType::class, [
                'label' => 'Czy jest popularnym produktem?',
                'required' => false,
            ])
            ->add('allegro_url', TextType::class, [
                'label' => 'Link do produktu Allegro',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'edition' => true,
        ]);
    }
}

