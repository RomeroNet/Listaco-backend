<?php

namespace App\Domain\Listing;

interface ListingRepositoryInterface
{
    /**
     * @throws ListingNotFoundException
     */
    public function findById(string $id): Listing;
    public function save(Listing $listing): Listing;
}
