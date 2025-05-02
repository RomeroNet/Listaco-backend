<?php

namespace App\Listing\Infrastructure\Database\Repository;

use App\Listing\Domain\Listing;
use App\Listing\Domain\ListingNotFoundException;
use App\Listing\Domain\ListingRepositoryInterface;
use App\Listing\Infrastructure\Database\Model\ListingModel as ListingModel;

readonly class EloquentListingRepository implements ListingRepositoryInterface
{
    public function __construct(
        private ListingModel $model
    ) {
    }

    /**
     * @throws ListingNotFoundException
     */
    public function findById(string $id): Listing
    {
        /** @var ListingModel|null $model */
        $model = $this->model
            ->where('id', $id)
            ->first();

        if ($model === null) {
            throw ListingNotFoundException::fromUuid($id);
        }

        return new Listing(
            $model->id,
            $model->title,
            $model->description
        );
    }

    /**
     * @throws ListingNotFoundException
     */
    public function deleteByUuid(string $uuid): void
    {
        $model = $this->model
            ->where('id', $uuid)
            ->first();

        if ($model === null) {
            throw ListingNotFoundException::fromUuid($uuid);
        }

        $model->delete();
    }

    /**
     * @throws ListingNotFoundException
     */
    public function update(string $uuid, string $title, ?string $description): Listing
    {
        $model = $this->model
            ->where('id', $uuid)
            ->first();

        if ($model === null) {
            throw ListingNotFoundException::fromUuid($uuid);
        }

        $model->title = $title;
        $model->description = $description;

        $model->save();

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
