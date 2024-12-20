<?php

use App\Application\UseCase\Listing\CreateListingUseCase;
use App\Domain\Listing\Listing;
use App\Infrastructure\Common\Uuid\RamseyUuidFactory;
use App\Infrastructure\Database\Listing\EloquentListingRepository;
use App\Infrastructure\Database\Listing\ListingModel;
use App\Infrastructure\Http\Controllers\Listing\ListingController;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;

covers(
    ListingController::class,
    CreateListingUseCase::class,
    EloquentListingRepository::class,
    Listing::class,
    RamseyUuidFactory::class
);


it('should create a list', function (
    bool $hasDescription
) {
    $faker = Factory::create();

    $title = $faker->sentence;
    $description = $faker->paragraph;
    $data = [
        'title' => $title
    ];

    if ($hasDescription) {
        $data['description'] = $description;
    }

    $creationResponse = $this->postJson('/api/listing', $data);

    /** @var ListingModel $databaseListing */
    $databaseListing = ListingModel::first();

    $creationResponse->assertStatus(Response::HTTP_CREATED);
    $creationResponse->assertJson(['message' => 'Created', 'id' => $databaseListing->id]);
    expect($databaseListing->title)->toBe($title)
        ->and($databaseListing->description)->toBe($hasDescription ? $description : null);
})->with([
    'when the list has a description' => [
        'hasDescription' => true
    ],
    'when the list does not have a description' => [
        'hasDescription' => false
    ]
]);

it('should catch a server error when creating a list', function () {
    $faker = Factory::create();

    forceDatabaseError();

    $title = $faker->sentence;
    $description = $faker->paragraph;

    $creationResponse = $this->postJson('/api/listing', [
        'title' => $title,
        'description' => $description
    ]);

    $creationResponse->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    $creationResponse->assertJson(['message' => 'Server Error']);
});
