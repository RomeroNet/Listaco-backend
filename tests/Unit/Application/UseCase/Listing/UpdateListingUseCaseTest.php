<?php

use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;

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
