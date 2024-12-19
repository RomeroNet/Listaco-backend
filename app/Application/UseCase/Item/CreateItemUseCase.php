<?php

namespace App\Application\UseCase\Item;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Item\Item;
use App\Domain\Item\ItemRepositoryInterface;

class CreateItemUseCase
{
    public function __construct(
        private readonly UuidFactoryInterface $uuidFactory,
        private readonly ItemRepositoryInterface $itemRepository
    ) {
    }

    public function handle(string $name, string $listingId): Item
    {
        $uuid = $this->uuidFactory->generate();

        return $this->itemRepository->save(new Item(
            $uuid,
            $name,
            false,
            $listingId
        ));
    }
}
