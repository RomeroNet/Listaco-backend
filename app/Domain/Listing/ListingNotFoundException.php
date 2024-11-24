<?php

namespace App\Domain\Listing;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ListingNotFoundException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }

    public static function fromUuid(string $uuid): self
    {
        return new self("ListingModel with ID $uuid not found.");
    }
}
