<?php

namespace App\Infrastructure\Http\Controllers\Item;

use App\Application\UseCase\Item\CreateItemUseCase;
use App\Infrastructure\Http\Requests\Item\CreateItemRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ItemController extends Controller
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly CreateItemUseCase $createItemUseCase
    ) {
    }

    public function post(CreateItemRequest $request): JsonResponse
    {
        try {
            $name = $request->string('name');
            $listingId = $request->string('listing_id');

            $item = $this->createItemUseCase->handle($name, $listingId);
        } catch (Throwable) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory
            ->json(['message' => 'Created', 'id' => $item->uuid], Response::HTTP_CREATED);
    }
}
