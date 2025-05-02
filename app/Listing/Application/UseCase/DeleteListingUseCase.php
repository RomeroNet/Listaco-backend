<?php

namespace App\Listing\Application\UseCase;

use App\Listing\Domain\ListingNotFoundException;
use App\Listing\Domain\ListingRepositoryInterface;

class DeleteListingUseCase
{
    public function __construct(
        private readonly ListingRepositoryInterface $listingRepository
    ) {
    }

    /**
     * @throws ListingNotFoundException
     */
    public function handle(string $uuid): void
    {
        $this->listingRepository
            ->deleteByUuid($uuid);
    }
}
