<?php

namespace App\Domain\Item;

readonly class Item
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $name,
        private readonly bool $isDone,
        private readonly string $listingUuid,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function listingUuid(): string
    {
        return $this->listingUuid;
    }
}
