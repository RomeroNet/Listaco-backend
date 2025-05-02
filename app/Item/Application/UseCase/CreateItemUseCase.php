<?php

namespace App\Item\Application\UseCase;


use App\Common\Uuid\Domain\UuidFactoryInterface;
use App\Item\Domain\Item;
use App\Item\Domain\ItemRepositoryInterface;

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
