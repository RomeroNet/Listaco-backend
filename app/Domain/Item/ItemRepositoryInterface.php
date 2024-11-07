<?php

namespace App\Domain\Item;

use App\Domain\Listing\Listing;

interface ItemRepositoryInterface
{
    public function findByListingUuid(string $listingUuid): ItemCollection;
    public function save(Item $item): void;
}
