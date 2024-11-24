<?php

namespace App\Infrastructure\Database\Listing;

use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Domain\Listing\ListingRepositoryInterface;
use App\Infrastructure\Database\Listing\ListingModel as ListingModel;

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
    public function update(string $uuid, ?string $title, ?string $description): Listing
    {
        $model = $this->model
            ->where('id', $uuid)
            ->first();

        if ($model === null) {
            throw ListingNotFoundException::fromUuid($uuid);
        }

        if ($title !== null) {
            $model->title = $title;
        }

        if ($description !== null) {
            $model->description = $description;
        }

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
