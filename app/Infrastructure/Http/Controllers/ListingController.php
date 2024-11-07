<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

class ListingController extends Controller
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly CreateListingUseCase $createListingUseCase
    ) {
    }

    public function post(CreateListingRequest $request): JsonResponse
    {
        try {
            $this->createListingUseCase->handle($request->input('title'), $request->input('description'));
            return $this->responseFactory
                ->json(['message' => 'Created'], 201);
        } catch (Throwable) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], 500);
        }
    }
}
