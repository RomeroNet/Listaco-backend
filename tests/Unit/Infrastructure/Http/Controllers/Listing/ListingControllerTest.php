<?php

use App\Common\Uuid\Infrastructure\Factory\RamseyUuidFactory;
use App\Listing\Application\UseCase\CreateListingUseCase;
use App\Listing\Domain\Listing;
use App\Listing\Infrastructure\Database\Model\ListingModel;
use App\Listing\Infrastructure\Database\Repository\EloquentListingRepository;
use App\Listing\Infrastructure\Http\Controller\ListingController;
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
