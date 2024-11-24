<?php

namespace App\Application\UseCase\Listing;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Domain\Listing\ListingRepositoryInterface;

class UpdateListingUseCase
{
    public function __construct(
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    /**
     * @throws ListingNotFoundException
     */
    public function handle(string $uuid, ?string $title, ?string $description): Listing
    {
        return $this->listingRepository->update($uuid, $title, $description);
    }
}
