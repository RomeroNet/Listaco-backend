<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\FetchListingUseCase\GetListingByUuidUseCase;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

class ListingController extends Controller
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly GetListingByUuidUseCase $getListingByUuidUseCase,
        private readonly CreateListingUseCase $createListingUseCase
    ) {
    }

    public function getByUuid(string $uuid): JsonResponse
    {
        try {
            $listing = $this->getListingByUuidUseCase->handle($uuid);
            return $this->responseFactory
                ->json($listing->toArray());
        } catch (Throwable $e) {
            // TODO: Add catch for 404.
            return $this->responseFactory
                ->json(['message' => 'Server Error'], 500);
        }
    }

    public function post(CreateListingRequest $request): JsonResponse
    {
        try {
            /** @var string $title */
            $title = $request->input('title');
            /** @var string $description */
            $description = $request->input('description');

            $listing = $this->createListingUseCase->handle($title, $description);
            return $this->responseFactory
                ->json(['message' => 'Created', 'uuid' => $listing->uuid()], 201);
        } catch (Throwable) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], 500);
        }
    }
}
