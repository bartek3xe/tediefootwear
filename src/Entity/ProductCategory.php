<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Translation\ProductCategoryTranslation;
use App\Repository\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProductCategory extends AbstractProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    private Collection $products;

    #[ORM\OneToMany(targetEntity: ProductCategoryTranslation::class, mappedBy: 'category', cascade: ['persist', 'remove'])]
    private Collection $translations;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    public function addTranslation(ProductCategoryTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setCategory($this);
        }

        return $this;
    }

    public function removeTranslation(ProductCategoryTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getCategory() === $this) {
                $translation->setCategory(null);
            }
        }

        return $this;
    }

    public function getTranslation(string $locale): ?ProductCategoryTranslation
    {
        $filteredTranslations = array_filter(
            $this->translations->toArray(),
            function(ProductCategoryTranslation $translation) use ($locale) {
                return $translation->getLanguage() === $locale;
            },
        );

        return array_shift($filteredTranslations) ?: null;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(Collection $translations): static
    {
        $this->translations = $translations;

        return $this;
    }
}
