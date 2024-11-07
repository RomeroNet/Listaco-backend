<?php

namespace App\Domain\Listing;

use Illuminate\Contracts\Support\Arrayable;

readonly class Listing implements Arrayable
{
    public function __construct(
        private string $uuid,
        private string $title,
        private ?string $description,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->uuid(),
            'title' => $this->title(),
            'description' => $this->description(),
        ];
    }
}
