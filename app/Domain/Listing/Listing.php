<?php

namespace App\Domain\Listing;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|null>
 */
readonly class Listing implements Arrayable
{
    public function __construct(
        private(set) string $uuid,
        private(set) string $title,
        private(set) ?string $description,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
