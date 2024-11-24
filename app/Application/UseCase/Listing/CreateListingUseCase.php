<?php

namespace App\Application\UseCase\Listing;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;

class CreateListingUseCase
{
    public function __construct(
        private readonly UuidFactoryInterface $uuidFactory,
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    public function handle(string $title, ?string $description): Listing
    {
        $uuid = $this->uuidFactory->generate();
        return $this->listingRepository->save(new Listing(
            $uuid,
            $title,
            $description
        ));
    }
}
