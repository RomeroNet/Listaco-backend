<?php

namespace App\Application\UseCase\DeleteListingUseCase;

use App\Domain\Listing\ListingNotFoundException;
use App\Domain\Listing\ListingRepositoryInterface;

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
