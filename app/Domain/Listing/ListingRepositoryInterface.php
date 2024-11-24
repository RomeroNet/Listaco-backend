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

    public function save(Listing $listing): Listing;
}
