<?php

namespace App\Application\UseCase\FetchListingUseCase;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;

class GetListingByUuidUseCase
{
    public function __construct(
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    public function handle(string $uuid): Listing
    {
        return $this->listingRepository
            ->findById($uuid);
    }
}
