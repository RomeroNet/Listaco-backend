<?php

namespace App\Domain\Item;

use Illuminate\Support\Collection;

interface ItemRepositoryInterface
{
    /**
     * @return Collection<int, Item>
     */
    public function findByListingUuid(string $listingUuid): Collection;
    public function save(Item $item): Item;
}
