<?php

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Infrastructure\Common\Uuid\RamseyUuidFactory;
use App\Infrastructure\Database\Repository\EloquentListingRepository;
use App\Infrastructure\Http\Controllers\ListingController;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Database\Model\Listing as ListingModel;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);
covers(
    ListingController::class,
    GetListingByUuidUseCase::class,
    CreateListingUseCase::class,
    EloquentListingRepository::class,
    Listing::class,
    ListingNotFoundException::class,
    RamseyUuidFactory::class
);

it('should create a list, then fetch it', function (
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

    $databaseListing = ListingModel::firstOrFail();

    $fetchResponse = $this->getJson("/api/listing/{$databaseListing->id}");

    $creationResponse->assertStatus(Response::HTTP_CREATED);
    $creationResponse->assertJson(['message' => 'Created', 'id' => $databaseListing->id]);

    $fetchResponse->assertStatus(Response::HTTP_OK);
    $fetchResponse->assertJson([
        'id' => $databaseListing->id,
        'title' => $title,
        'description' => $hasDescription ? $description : null
    ]);
})->with([
    'when the list has a description' => [
        'hasDescription' => true
    ],
    'when the list does not have a description' => [
        'hasDescription' => false
    ]
]);

it('should create a listing, then remove it, and finally try to fetch it', function () {
    $faker = Factory::create();

    $title = $faker->sentence;
    $description = $faker->paragraph;

    $creationResponse = $this->postJson('/api/listing', [
        'title' => $title,
        'description' => $description
    ]);

    $databaseListing = ListingModel::firstOrFail();

    $deletionResponse = $this->deleteJson("/api/listing/{$databaseListing->id}");
    $fetchResponse = $this->getJson("/api/listing/{$databaseListing->id}");

    $creationResponse->assertStatus(Response::HTTP_CREATED);
    $creationResponse->assertJson(['message' => 'Created', 'id' => $databaseListing->id]);
    $deletionResponse->assertStatus(Response::HTTP_OK);
    $deletionResponse->assertJson(['message' => 'Deleted']);
    $fetchResponse->assertStatus(Response::HTTP_NOT_FOUND);
    $fetchResponse->assertJson(['message' => "Listing with ID {$databaseListing->id} not found."]);
});

it('should return a not found response when fetching', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('Listing with ID %s not found.', $uuid);

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $response->assertJson(['message' => $message]);
});

it('should return a not found response when deleting', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('Listing with ID %s not found.', $uuid);

    $response = $this->deleteJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $response->assertJson(['message' => $message]);
});
