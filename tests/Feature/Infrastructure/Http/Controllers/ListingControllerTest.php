<?php

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Infrastructure\Common\Uuid\RamseyUuidFactory;
use App\Infrastructure\Database\Repository\EloquentListingRepository;
use App\Infrastructure\Http\Controllers\ListingController;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use Faker\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Database\Model\Listing as ListingModel;

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

//region POST

it('should create a list with POST method', function (
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

    $response = $this->postJson('/api/listing', $data);

    $listing = ListingModel::query()->first();

    expect($listing)
        ->toBeInstanceOf(ListingModel::class)
        ->title->toBe($title)
        ->description->toBe($hasDescription ? $description : null);
    $response->assertStatus(201);
    $response->assertJson(['message' => 'Created', 'id' => $listing->id]);
})->with([
    'when the list has a description' => [
        'hasDescription' => true
    ],
    'when the list does not have a description' => [
        'hasDescription' => false
    ]
]);

it('should return a server error with POST method', function () {
    $faker = Factory::create();

    $title = $faker->sentence;
    $description = $faker->paragraph;

    $createListingUseCase = Mockery::mock(CreateListingUseCase::class);

    $createListingUseCase
        ->shouldReceive('handle')
        ->with($title, $description)
        ->andThrow(new Exception);

    $controller = new ListingController(
        $this->app->make(ResponseFactory::class),
        $this->app->make(GetListingByUuidUseCase::class),
        $createListingUseCase
    );

    $response = $controller->post(new CreateListingRequest([
        'title' => $title,
        'description' => $description
    ]));

    expect($response->getStatusCode())->toBe(500)
        ->and($response->getContent())->toBe(json_encode(['message' => 'Server Error']));
});

it('should return a server error with GET method', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;

    $getListingUseCase = Mockery::mock(GetListingByUuidUseCase::class);

    $getListingUseCase
        ->shouldReceive('handle')
        ->with($uuid)
        ->andThrow(new Exception);

    $controller = new ListingController(
        $this->app->make(ResponseFactory::class),
        $getListingUseCase,
        $this->app->make(CreateListingUseCase::class)
    );

    $response = $controller->getByUuid(new GetListingRequest([
        'id' => $uuid
    ]));

    expect($response->getStatusCode())->toBe(500)
        ->and($response->getContent())->toBe(json_encode(['message' => 'Server Error']));
});

//endregion

//region GET

it('should fetch a list with GET method', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;

    $listing = new ListingModel([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);

    $listing->save();

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);
});

it('should return a not found response with GET method', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('Listing with ID %s not found.', $uuid);

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus(404);
    $response->assertJson(['message' => $message]);
});

//endregion
