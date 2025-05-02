<?php

use App\Listing\Application\UseCase\GetListingByUuidUseCase;
use App\Listing\Domain\Listing;
use App\Listing\Domain\ListingRepositoryInterface;

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
