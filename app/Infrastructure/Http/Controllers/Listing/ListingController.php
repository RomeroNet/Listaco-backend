<?php

namespace App\Infrastructure\Http\Controllers\Listing;

use App\Application\UseCase\Listing\CreateListingUseCase;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
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
            /** @var string $title */
            $title = $request->input('title');
            /** @var string $description */
            $description = $request->input('description');

            $listing = $this->createListingUseCase->handle($title, $description);
        } catch (Throwable $e) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory
            ->json(['message' => 'Created', 'id' => $listing->uuid], Response::HTTP_CREATED);
    }
}
