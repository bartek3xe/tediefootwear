<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractProduct
{
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: "json")]
    private array $name = [];

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): array
    {
        return $this->name;
    }

    public function setName(array $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->name['pl'] ?? $this->name['en'] ?? null;
    }

    public function getNameByLanguage(string $language): ?string
    {
        if (isset($this->name[$language])) {
            return $this->name[$language];
        }

        return $this->name['en'] ?? null;
    }
}
