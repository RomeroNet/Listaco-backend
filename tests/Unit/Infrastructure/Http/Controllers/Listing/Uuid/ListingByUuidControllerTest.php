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

it('should fetch a list', function (
    bool $listExists,
    int $expectedStatus
) {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;

    // TODO: Use factories

    if ($listExists) {
        $model = new ListingModel([
            'id' => $uuid,
            'title' => $title,
            'description' => $description
        ]);

        $model->save();

        $expectedResponse = [
            'id' => $uuid,
            'title' => $title,
            'description' => $description
        ];
    }

    if (! $listExists) {
        $expectedResponse = [
            'message' => 'ListingModel with ID ' . $uuid . ' not found.'
        ];
    }

    if ($expectedStatus === Response::HTTP_INTERNAL_SERVER_ERROR) {
        forceDatabaseError();

        $expectedResponse = ['message' => 'Server Error'];
    }

    $response = $this->getJson("/api/listing/$uuid");

    $response->assertStatus($expectedStatus);
    $response->assertJson($expectedResponse);
})->with([
    'when the list exists' => [
        'listExists' => true,
        'expectedStatus' => Response::HTTP_OK
    ],
    'when the list does not exist' => [
        'listExists' => false,
        'expectedStatus' => Response::HTTP_NOT_FOUND
    ],
    'when the endpoint throws a server error' => [
        'listExists' => false,
        'expectedStatus' => Response::HTTP_INTERNAL_SERVER_ERROR
    ]
]);

it('should update a list', function (
    bool $listExists,
    bool $hasDescription,
    int $expectedStatus
) {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;
    $newTitle = $faker->sentence;
    $newDescription = $hasDescription ? $faker->paragraph : null;

    if ($listExists) {
        $model = new ListingModel([
            'id' => $uuid,
            'title' => $title,
            'description' => $description
        ]);
        $model->save();
    }

    $request = [
        'title' => $newTitle,
    ];

    if ($hasDescription) {
        $request['description'] = $newDescription;
    }

    if ($listExists) {
        $expectedResponse = [
            'message' => 'Updated',
            'id' => $uuid
        ];
    } else {
        $expectedResponse = [
            'message' => 'ListingModel with ID ' . $uuid . ' not found.'
        ];
    }

    if ($expectedStatus === Response::HTTP_INTERNAL_SERVER_ERROR) {
        forceDatabaseError();
        $expectedResponse = ['message' => 'Server Error'];
    }

    $response = $this->patchJson("/api/listing/$uuid", $request);

    $response->assertStatus($expectedStatus);
    $response->assertJson($expectedResponse);

    if (! $listExists) {
        return;
    }

    /** @var ListingModel $updatedModel */
    $updatedModel = ListingModel::find($uuid);

    expect($updatedModel->title)->toBe($newTitle)
        ->and($updatedModel->description)->toBe($newDescription);
})->with([
    'when the list exists and the description is updated to a text' => [
        'listExists' => true,
        'hasDescription' => true,
        'expectedStatus' => Response::HTTP_OK,
    ],
    'when the list exists and the description is updated to null' => [
        'listExists' => true,
        'hasDescription' => false,
        'expectedStatus' => Response::HTTP_OK,
    ],
    'when the list does not exist' => [
        'listExists' => false,
        'hasDescription' => false,
        'expectedStatus' => Response::HTTP_NOT_FOUND,
    ],
    'when the endpoint throws a server error' => [
        'listExists' => false,
        'hasDescription' => false,
        'expectedStatus' => Response::HTTP_INTERNAL_SERVER_ERROR,
    ]
]);

it('should delete a list', function (
    bool $listExists,
    int $expectedStatus
) {
    $faker = Factory::create();

    $uuid = $faker->uuid;
    $title = $faker->sentence;
    $description = $faker->paragraph;

    if ($listExists) {
        $model = new ListingModel([
            'id' => $uuid,
            'title' => $title,
            'description' => $description
        ]);
        $model->save();

        $expectedResponse = [
            'message' => 'Deleted'
        ];
    } else {
        $expectedResponse = [
            'message' => 'ListingModel with ID ' . $uuid . ' not found.'
        ];
    }

    if ($expectedStatus === Response::HTTP_INTERNAL_SERVER_ERROR) {
        forceDatabaseError();
        $expectedResponse = ['message' => 'Server Error'];
    }

    $response = $this->deleteJson("/api/listing/$uuid");

    $response->assertStatus($expectedStatus);
    $response->assertJson($expectedResponse);

    if (! $listExists) {
        return;
    }

    $deletedModel = ListingModel::find($uuid);

    expect($deletedModel)->toBeNull();
})->with([
    'when the list exists' => [
        'listExists' => true,
        'expectedStatus' => Response::HTTP_OK
    ],
    'when the list does not exist' => [
        'listExists' => false,
        'expectedStatus' => Response::HTTP_NOT_FOUND
    ],
    'when the endpoint throws a server error' => [
        'listExists' => false,
        'expectedStatus' => Response::HTTP_INTERNAL_SERVER_ERROR
    ]
]);
