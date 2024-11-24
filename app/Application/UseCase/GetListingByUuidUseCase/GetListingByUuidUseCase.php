<?php

namespace App\Application\UseCase\GetListingByUuidUseCase;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Domain\Listing\ListingRepositoryInterface;

class GetListingByUuidUseCase
{
    public function __construct(
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    /**
     * @throws ListingNotFoundException
     */
    public function handle(string $uuid): Listing
    {
        return $this->listingRepository
            ->findById($uuid);
    }
}
