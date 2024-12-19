<?php

use App\Application\UseCase\Listing\DeleteListingUseCase;
use App\Application\UseCase\Listing\GetListingByUuidUseCase;
use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Infrastructure\Http\Controllers\Listing\Uuid\ListingByUuidController;
use App\Infrastructure\Http\Requests\Listing\DeleteListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Faker\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

covers(
    ListingByUuidController::class
);

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

    $controller = new ListingByUuidController(
        $responseFactory,
        $getListingByUuidUseCase,
        Mockery::mock(DeleteListingUseCase::class),
        Mockery::mock(UpdateListingUseCase::class)
    );

    $result = $controller->get($request);

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

    $controller = new ListingByUuidController(
        $responseFactory,
        Mockery::mock(GetListingByUuidUseCase::class),
        $deleteListingUseCase,
        Mockery::mock(UpdateListingUseCase::class)
    );

    $result = $controller->delete($request);

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
        ->shouldReceive('has')
        ->with('description')
        ->andReturn(true);
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

    $controller = new ListingByUuidController(
        $responseFactory,
        Mockery::mock(GetListingByUuidUseCase::class),
        Mockery::mock(DeleteListingUseCase::class),
        $updateListingUseCase
    );

    $result = $controller->patch($request);

    expect($result)->toBe($response);
});
