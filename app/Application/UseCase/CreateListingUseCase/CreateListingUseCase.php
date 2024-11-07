<?php

namespace App\Application\UseCase\CreateListingUseCase;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;

readonly class CreateListingUseCase
{
    public function __construct(
        private UuidFactoryInterface $uuidFactory,
        private ListingRepositoryInterface $listingRepository
    ) {
    }

    public function handle(string $title, ?string $description): void
    {

        $uuid = $this->uuidFactory->generate();
        $test = new Listing($uuid, $title, $description);
        $this->listingRepository->save($test);
    }
}
