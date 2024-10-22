<?php

declare(strict_types=1);

namespace App\Service\Builder\Config;

class Field
{
    public function __construct(
        private readonly string $name,
        private readonly string $source,
        private readonly ?string $url = null,
        private readonly ?array $parameters = null,
    ) {
    }

    public function toArray(): array
    {
        $fieldArray = [
            'source' => $this->source,
        ];

        if ('api' === $this->source) {
            $fieldArray['url'] = $this->url;
            $fieldArray['parameters'] = $this->parameters;
        }

        return $fieldArray;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
