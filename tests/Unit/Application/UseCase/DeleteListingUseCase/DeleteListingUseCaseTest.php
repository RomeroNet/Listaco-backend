<?php

use App\Application\UseCase\DeleteListingUseCase\DeleteListingUseCase;
use App\Domain\Listing\ListingRepositoryInterface;

covers(
    DeleteListingUseCase::class
);

it('should delete a list', function () {
    $faker = Faker\Factory::create();

    $uuid = $faker->uuid();

    $listingRepository = Mockery::mock(ListingRepositoryInterface::class);

    $listingRepository
        ->shouldReceive('deleteByUuid')
        ->with($uuid);

    $deleteListingUseCase = new DeleteListingUseCase($listingRepository);

    try {
        $deleteListingUseCase->handle($uuid);
        $result = true;
    } catch (Throwable) {
        $result = false;
    }

    expect($result)->toBeTrue();
});
