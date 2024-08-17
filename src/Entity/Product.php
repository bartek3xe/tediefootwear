<?php

declare(strict_types=1);

namespace App\Entity;

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

    #[ORM\Column(type: Types::JSON)]
    private array $description = [];

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isNew = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isTop = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $allegro_url = null;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function setDescription(array $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): static
    {
        $this->category = $category;

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
}