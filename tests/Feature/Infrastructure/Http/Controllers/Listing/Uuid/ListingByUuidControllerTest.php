<?php

use App\Application\UseCase\Listing\DeleteListingUseCase;
use App\Application\UseCase\Listing\GetListingByUuidUseCase;
use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Domain\Listing\Listing;
use App\Domain\Listing\ListingNotFoundException;
use App\Infrastructure\Database\Listing\EloquentListingRepository;
use App\Infrastructure\Database\Listing\ListingModel;
use App\Infrastructure\Http\Controllers\Listing\Uuid\ListingByUuidController;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;

covers(
    ListingByUuidController::class,
    GetListingByUuidUseCase::class,
    DeleteListingUseCase::class,
    UpdateListingUseCase::class,
    EloquentListingRepository::class,
    Listing::class,
    ListingNotFoundException::class,
);

it('should fetch a list', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;

    // TODO: Use factories
    $model = new ListingModel([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);
    $model->save();

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_OK);
    $response->assertJson([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);
});

it('should update a list', function (
    bool $hasDescription
) {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;
    $newTitle = $faker->sentence;
    $newDescription = $hasDescription ? $faker->paragraph : null;

    $model = new ListingModel([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);

    $model->save();

    $request = [
        'title' => $newTitle,
    ];

    if ($hasDescription) {
        $request['description'] = $newDescription;
    }

    $response = $this->patchJson("/api/listing/$uuid", $request);

    $response->assertStatus(Response::HTTP_OK);
    $response->assertJson([
        'message' => 'Updated',
        'id' => $uuid,
    ]);

    /** @var ListingModel $updatedModel */
    $updatedModel = ListingModel::find($uuid);

    expect($updatedModel->title)->toBe($newTitle)
        ->and($updatedModel->description)->toBe($newDescription);
})->with([
    'when the list has a description' => [
        'hasDescription' => true
    ],
    'when the list does not have a description' => [
        'hasDescription' => false
    ]
]);

it('should delete a list', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;

    $model = new ListingModel([
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ]);
    $model->save();

    $response = $this->deleteJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_OK);
    $response->assertJson(['message' => 'Deleted']);

    $deletedModel = ListingModel::find($uuid);

    expect($deletedModel)->toBeNull();
});

it('should return a not found response when fetching', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('ListingModel with ID %s not found.', $uuid);

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $response->assertJson(['message' => $message]);
});

it('should return a not found response when deleting', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('ListingModel with ID %s not found.', $uuid);

    $response = $this->deleteJson("/api/listing/$uuid");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $response->assertJson(['message' => $message]);
});

it('should return a not found response when updating', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $message = sprintf('ListingModel with ID %s not found.', $uuid);

    $response = $this->patchJson("/api/listing/$uuid", [
        'title' => $faker->sentence,
        'description' => $faker->paragraph
    ]);

    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $response->assertJson(['message' => $message]);
});
