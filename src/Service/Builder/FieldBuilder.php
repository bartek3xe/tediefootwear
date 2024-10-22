<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Service\Builder\Config\Field;

class FieldBuilder
{
    private array $fields = [];

    public function addField(string $name, string $source, ?string $url = null, ?array $parameters = null): self
    {
        $this->fields[] = new Field($name, $source, $url, $parameters);

        return $this;
    }

    public function build(): array
    {
        $result = [];
        foreach ($this->fields as $field) {
            $result[$field->getName()] = $field->toArray();
        }

        return $result;
    }
}
