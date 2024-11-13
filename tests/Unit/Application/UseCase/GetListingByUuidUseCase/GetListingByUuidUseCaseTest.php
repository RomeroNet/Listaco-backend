<?php

use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;

covers(
    GetListingByUuidUseCase::class
);

it('should find a list', function () {
    $faker = Faker\Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->text;

    $listing = new Listing(
        $uuid,
        $title,
        $description
    );

    $repository = Mockery::mock(ListingRepositoryInterface::class);

    $repository
        ->shouldReceive('findById')
        ->with($uuid)
        ->andReturn($listing);

    $useCase = new GetListingByUuidUseCase($repository);

    $result = $useCase->handle($uuid);

    expect($result)->toBe($listing);
});
