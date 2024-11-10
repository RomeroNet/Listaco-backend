<?php

namespace App\Infrastructure\Database\Repository;

use App\Domain\Item\Item;
use App\Domain\Item\ItemRepositoryInterface;
use App\Infrastructure\Database\Model\Item as ItemModel;
use Illuminate\Support\Collection;

readonly class EloquentItemRepository implements ItemRepositoryInterface
{
    public function __construct(
        private ItemModel $model
    ) {
    }

    /**
     * @return Collection<int, Item>
     */
    public function findByListingUuid(string $listingUuid): Collection
    {
        /** @var Collection<int, ItemModel> $items */
        $items = $this->model
            ->where('listing_id', $listingUuid)
            ->get();

        return $items->map(fn(ItemModel $item) => Item::fromArray($item->toArray()));
    }

    public function save(Item $item): void
    {
        $this->model
            ->upsert($item->toArray(), ['id']);
    }
}
