<?php

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\DeleteListingUseCase\DeleteListingUseCase;
use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Infrastructure\Http\Controllers\ListingController;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use App\Infrastructure\Http\Requests\Listing\DeleteListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use Faker\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

it('should handle a server error when creating a list', function () {
    $faker = Factory::create();

    $title = $faker->sentence;
    $description = $faker->paragraph;

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $createListingUseCase = Mockery::mock(CreateListingUseCase::class);
    $request = Mockery::mock(CreateListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('input')
        ->with('title')
        ->andReturn($title);
    $request
        ->shouldReceive('input')
        ->with('description')
        ->andReturn($description);

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
        Mockery::mock(DeleteListingUseCase::class)
    );

    $result = $controller->post($request);

    expect($result)->toBe($response);
});

it('should handle a server error when fetching a list', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $getListingByUuidUseCase = Mockery::mock(GetListingByUuidUseCase::class);
    $request = Mockery::mock(GetListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('string')
        ->with('id')
        ->andReturn($uuid);

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
        Mockery::mock(DeleteListingUseCase::class)
    );

    $result = $controller->getByUuid($request);

    expect($result)->toBe($response);
});

it('should handle a server error when deleting a list', function () {
    $faker = Factory::create();

    $uuid = $faker->uuid;

    $responseFactory = Mockery::mock(ResponseFactory::class);
    $deleteListingUseCase = Mockery::mock(DeleteListingUseCase::class);
    $request = Mockery::mock(DeleteListingRequest::class);
    $response = Mockery::mock(JsonResponse::class);

    $request
        ->shouldReceive('string')
        ->with('id')
        ->andReturn($uuid);

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
        $deleteListingUseCase
    );

    $result = $controller->deleteByUuid($request);

    expect($result)->toBe($response);
});
