<?php

namespace App\Infrastructure\Database\Repository;

use App\Domain\Item\Item;
use App\Domain\Item\ItemCollection;
use App\Domain\Item\ItemRepositoryInterface;
use App\Infrastructure\Database\Model\Item as ItemModel;

readonly class EloquentItemRepository implements ItemRepositoryInterface
{
    public function __construct(
        private ItemModel $model
    ) {
    }

    public function findByListingUuid(string $listingUuid): ItemCollection
    {
        return $this->model
            ->where('listing_id', $listingUuid)
            ->get()
            ->map(function (ItemModel $item) {
                return new Item(
                    $item->id,
                    $item->name,
                    $item->done,
                    $item->listing_id
                );
            });
    }

    public function save(Item $item): void
    {
        $this->model
            ->upsert($item->toArray());
    }
}
