<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Translation\ProductCategoryTranslation;
use App\Entity\Translation\ProductTranslation;
use App\Enum\LanguageEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractProduct
{
    abstract public function getTranslation(string $locale): ProductTranslation|ProductCategoryTranslation|null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNameByLanguage(string $language): ?string
    {
        /** @var Product|ProductCategory $this */
        $translation = $this->getTranslation($language);

        return $translation ? $translation->getName() : $this->getDefaultTranslation()?->getName();
    }

    public function getDescriptionByLanguage(string $language): ?string
    {
        /** @var Product $this */
        $translation = $this->getTranslation($language);

        return $translation ? $translation->getDescription() : $this->getDefaultTranslation()?->getDescription();
    }

    public function getDefaultTranslation(): ProductTranslation|ProductCategoryTranslation|null
    {
        return $this->getTranslation(LanguageEnum::DEFAULT_LOCALE_VALUE);
    }
}
