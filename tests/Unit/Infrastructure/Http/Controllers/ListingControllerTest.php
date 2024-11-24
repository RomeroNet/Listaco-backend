<?php

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\DeleteListingUseCase\DeleteListingUseCase;
use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Infrastructure\Http\Controllers\ListingController;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use App\Infrastructure\Http\Requests\Listing\DeleteListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Faker\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

covers(
    ListingController::class
);

it('should handle a server error when creating a list', function () {
    $faker = Factory::create();

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $createListingUseCase = Mockery::mock(CreateListingUseCase::class);
    $request = Mockery::mock(CreateListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('input')
        ->with('title')
        ->andReturn($faker->sentence);
    $request
        ->shouldReceive('input')
        ->with('description')
        ->andReturn($faker->paragraph);

    $createListingUseCase
        ->shouldReceive('handle')
        ->andThrow(new Exception());

    $responseFactory
        ->shouldReceive('json')
        ->with(['message' => 'Server Error'], 500)
        ->andReturn($response);

    $controller = new ListingController(
        $responseFactory,
        Mockery::mock(GetListingByUuidUseCase::class),
        $createListingUseCase,
        Mockery::mock(DeleteListingUseCase::class),
        Mockery::mock(UpdateListingUseCase::class)
    );

    $result = $controller->post($request);

    expect($result)->toBe($response);
});

it('should handle a server error when fetching a list', function () {
    $faker = Factory::create();

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $getListingByUuidUseCase = Mockery::mock(GetListingByUuidUseCase::class);
    $request = Mockery::mock(GetListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('string')
        ->with('uuid')
        ->andReturn($faker->uuid);

    $getListingByUuidUseCase
        ->shouldReceive('handle')
        ->andThrow(new Exception());

    $responseFactory
        ->shouldReceive('json')
        ->with(['message' => 'Server Error'], 500)
        ->andReturn($response);

    $controller = new ListingController(
        $responseFactory,
        $getListingByUuidUseCase,
        Mockery::mock(CreateListingUseCase::class),
        Mockery::mock(DeleteListingUseCase::class),
        Mockery::mock(UpdateListingUseCase::class)
    );

    $result = $controller->getByUuid($request);

    expect($result)->toBe($response);
});

it('should handle a server error when deleting a list', function () {
    $faker = Factory::create();

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $deleteListingUseCase = Mockery::mock(DeleteListingUseCase::class);
    $request = Mockery::mock(DeleteListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('string')
        ->with('uuid')
        ->andReturn($faker->uuid);

    $deleteListingUseCase
        ->shouldReceive('handle')
        ->andThrow(new Exception());

    $responseFactory
        ->shouldReceive('json')
        ->with(['message' => 'Server Error'], 500)
        ->andReturn($response);

    $controller = new ListingController(
        $responseFactory,
        Mockery::mock(GetListingByUuidUseCase::class),
        Mockery::mock(CreateListingUseCase::class),
        $deleteListingUseCase,
        Mockery::mock(UpdateListingUseCase::class)
    );

    $result = $controller->deleteByUuid($request);

    expect($result)->toBe($response);
});

it('should handle a server error when updating a list', function () {
    $faker = Factory::create();

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $updateListingUseCase = Mockery::mock(UpdateListingUseCase::class);
    $request = Mockery::mock(UpdateListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('string')
        ->with('uuid')
        ->andReturn($faker->uuid);
    $request
        ->shouldReceive('string')
        ->with('title')
        ->andReturn($faker->sentence);
    $request
        ->shouldReceive('string')
        ->with('description')
        ->andReturn($faker->paragraph);

    $updateListingUseCase
        ->shouldReceive('handle')
        ->andThrow(new Exception());

    $responseFactory
        ->shouldReceive('json')
        ->with(['message' => 'Server Error'], 500)
        ->andReturn($response);

    $controller = new ListingController(
        $responseFactory,
        Mockery::mock(GetListingByUuidUseCase::class),
        Mockery::mock(CreateListingUseCase::class),
        Mockery::mock(DeleteListingUseCase::class),
        $updateListingUseCase
    );

    $result = $controller->patch($request);

    expect($result)->toBe($response);
});
