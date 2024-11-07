<?php

namespace App\Infrastructure\Database\Repository;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;
use App\Infrastructure\Database\Model\Listing as ListingModel;

readonly class EloquentListingRepository implements ListingRepositoryInterface
{
    public function __construct(
        private ListingModel $model
    ) {
    }

    public function searchByUuid(string $uuid): Listing
    {
        return $this
            ->model
            ->where('uuid', $uuid)
            ->first();
    }

    public function save(Listing $listing): void
    {
        $this
            ->model
            ->create($listing->toArray());
    }
}
