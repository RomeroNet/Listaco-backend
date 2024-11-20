<?php

namespace App\Domain\Listing;

use Exception;

class ListingNotFoundException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message, 404);
    }

    public static function fromUuid(string $uuid): self
    {
        return new self("Listing with ID $uuid not found.");
    }
}
