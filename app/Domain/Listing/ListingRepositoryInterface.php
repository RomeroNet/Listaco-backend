<?php

namespace App\Domain\Listing;

interface ListingRepositoryInterface
{
    /**
     * @throws ListingNotFoundException
     */
    public function findById(string $id): Listing;

    /**
     * @throws ListingNotFoundException
     */
    public function deleteByUuid(string $uuid): void;

    /**
     * @throws ListingNotFoundException
     */
    public function update(string $uuid, ?string $title, ?string $description): Listing;

    public function save(Listing $listing): Listing;
}
