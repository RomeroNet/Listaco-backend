<?php

use App\Listing\Application\UseCase\UpdateListingUseCase;
use App\Listing\Domain\Listing;
use App\Listing\Domain\ListingRepositoryInterface;

covers(
    UpdateListingUseCase::class,
    Listing::class
);

it('should update a listing', function () {
    $faker = Faker\Factory::create();

    $uuid = $faker->uuid();
    $title = $faker->sentence();
    $description = $faker->paragraph();

    $listingRepository = Mockery::mock(ListingRepositoryInterface::class);
    $expectedListing = new Listing(
        $uuid,
        $title,
        $description
    );

    $listingRepository
        ->shouldReceive('update')
        ->with($uuid, $title, $description)
        ->andReturn($expectedListing);

    $service = new UpdateListingUseCase($listingRepository);

    $result = $service->handle($uuid, $title, $description);

    expect($result)->toBe($expectedListing);
});
