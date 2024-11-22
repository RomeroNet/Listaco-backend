<?php

namespace App\Domain\Item;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|bool>
 */
readonly class Item implements Arrayable
{
    public function __construct(
        private(set) string $uuid,
        private(set) string $name,
        private(set) bool $isDone,
        private(set) string $listingUuid,
    ) {
    }

    /**
     * @param array{
     *     id: string,
     *     name: string,
     *     done: bool,
     *     listing_id: string,
     * } $itemData
     */
    public static function fromArray(array $itemData): self
    {
        return new self(
            $itemData['id'],
            $itemData['name'],
            $itemData['done'],
            $itemData['listing_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'done' => $this->isDone,
            'listing_id' => $this->listingUuid,
        ];
    }
}
