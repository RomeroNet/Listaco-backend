<?php

namespace App\Domain\Listing;

interface ListingRepositoryInterface
{
    public function searchByUuid(string $uuid): Listing;
    public function save(Listing $listing): void;
}
