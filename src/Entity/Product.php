<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Translation\ProductTranslation;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product extends AbstractProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isNew = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isTop = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $allegro_url = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $etsy_url = null;

    #[ORM\ManyToMany(targetEntity: ProductCategory::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'products_categories')]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $files;

    #[ORM\OneToMany(targetEntity: ProductTranslation::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $translations;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): static
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function isTop(): bool
    {
        return $this->isTop;
    }

    public function setIsTop(bool $isTop): static
    {
        $this->isTop = $isTop;

        return $this;
    }

    public function getAllegroUrl(): ?string
    {
        return $this->allegro_url;
    }

    public function setAllegroUrl(?string $allegro_url): static
    {
        $this->allegro_url = $allegro_url;

        return $this;
    }

    public function getEtsyUrl(): ?string
    {
        return $this->etsy_url;
    }

    public function setEtsyUrl(?string $etsy_url): static
    {
        $this->etsy_url = $etsy_url;

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(ProductCategory $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(ProductCategory $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setProduct($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            if ($file->getProduct() === $this) {
                $file->setProduct(null);
            }
        }

        return $this;
    }

    public function addTranslation(ProductTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setProduct($this);
        }

        return $this;
    }

    public function removeTranslation(ProductTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getProduct() === $this) {
                $translation->setProduct(null);
            }
        }

        return $this;
    }

    public function getTranslation(string $locale): ?ProductTranslation
    {
        $filteredTranslations = array_filter(
            $this->translations->toArray(),
            function (ProductTranslation $translation) use ($locale) {
                return $translation->getLanguage() === $locale;
            }
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
