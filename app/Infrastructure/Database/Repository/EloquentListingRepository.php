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

    public function findById(string $id): Listing
    {
        /** @var ListingModel $model */
        $model = $this->model
            ->where('id', $id)
            ->first();

        return new Listing(
            $model->id,
            $model->title,
            $model->description
        );
    }

    public function save(Listing $listing): Listing
    {
        $this->model
            ->create($listing->toArray());

        return $listing;
    }
}
