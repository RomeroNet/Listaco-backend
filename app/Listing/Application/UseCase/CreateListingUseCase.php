<?php

namespace App\Listing\Application\UseCase;

use App\Common\Uuid\Domain\UuidFactoryInterface;
use App\Listing\Domain\Listing;
use App\Listing\Domain\ListingRepositoryInterface;

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
