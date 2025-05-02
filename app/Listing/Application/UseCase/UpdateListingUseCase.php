<?php

namespace App\Listing\Application\UseCase;

use App\Listing\Domain\Listing;
use App\Listing\Domain\ListingNotFoundException;
use App\Listing\Domain\ListingRepositoryInterface;

class UpdateListingUseCase
{
    public function __construct(
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    /**
     * @throws ListingNotFoundException
     */
    public function handle(string $uuid, string $title, ?string $description): Listing
    {
        return $this->listingRepository->update($uuid, $title, $description);
    }
}
