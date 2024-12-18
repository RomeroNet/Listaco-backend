<?php

use App\Application\UseCase\Listing\CreateListingUseCase;
use App\Infrastructure\Http\Controllers\Listing\ListingController;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
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
        $createListingUseCase
    );

    $result = $controller->post($request);

    expect($result)->toBe($response);
});
