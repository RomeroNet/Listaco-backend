<?php

namespace App\Domain\Item;

use Illuminate\Contracts\Support\Arrayable;

readonly class Item implements Arrayable
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

    public function toArray(): array
    {
        return [
            'id' => $this->uuid(),
            'name' => $this->name(),
            'done' => $this->isDone(),
            'listing_id' => $this->listingUuid(),
        ];
    }
}
