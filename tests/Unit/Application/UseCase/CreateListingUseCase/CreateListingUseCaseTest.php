<?php

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingRepositoryInterface;
use Mockery\Matcher\IsEqual;

covers(
    CreateListingUseCase::class,
    Listing::class
);

it('should create a list', function () {
    $faker = Faker\Factory::create();

    $uuid = $faker->uuid();
    $title = $faker->sentence();
    $description = $faker->paragraph();

    $expectedListing = new Listing(
        $uuid,
        $title,
        $description
    );

    $uuidFactory = Mockery::mock(UuidFactoryInterface::class);
    $listingRepository = Mockery::mock(ListingRepositoryInterface::class);

    $uuidFactory
        ->shouldReceive('generate')
        ->andReturn($uuid);
    $listingRepository
        ->shouldReceive('save')
        ->with(new IsEqual($expectedListing))
        ->andReturn($expectedListing);

    $createListingUseCase = new CreateListingUseCase($uuidFactory, $listingRepository);

    $result = $createListingUseCase->handle($title, $description);

    expect($result)->toBe($expectedListing);
});
