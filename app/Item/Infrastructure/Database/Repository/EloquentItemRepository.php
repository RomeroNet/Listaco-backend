<?php

namespace App\Item\Infrastructure\Database\Repository;

use App\Item\Domain\Item;
use App\Item\Domain\ItemRepositoryInterface;
use App\Item\Infrastructure\Database\Model\ItemModel;
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
