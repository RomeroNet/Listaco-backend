<?php

namespace App\Domain\Listing;

readonly class Listing
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $title,
        private readonly ?string $description,
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
}
