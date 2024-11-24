<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCase\CreateListingUseCase\CreateListingUseCase;
use App\Application\UseCase\DeleteListingUseCase\DeleteListingUseCase;
use App\Application\UseCase\GetListingByUuidUseCase\GetListingByUuidUseCase;
use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Domain\Listing\ListingNotFoundException;
use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use App\Infrastructure\Http\Requests\Listing\DeleteListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ListingController extends Controller
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly GetListingByUuidUseCase $getListingByUuidUseCase,
        private readonly CreateListingUseCase $createListingUseCase,
        private readonly DeleteListingUseCase $deleteListingUseCase,
        private readonly UpdateListingUseCase $updateListingUseCase
    ) {
    }

    public function getByUuid(GetListingRequest $request): JsonResponse
    {
        $uuid = $request->string('uuid');

        try {
            $listing = $this->getListingByUuidUseCase->handle($uuid);
            return $this->responseFactory
                ->json($listing->toArray());
        } catch (ListingNotFoundException $e) {
            return $this->responseFactory
                ->json(['message' => $e->getMessage()], $e->getCode());
        }  catch (Throwable) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        } catch (Throwable $e) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory
            ->json(['message' => 'Created', 'id' => $listing->uuid], Response::HTTP_CREATED);
    }

    public function deleteByUuid(DeleteListingRequest $request): JsonResponse
    {
        $uuid = $request->string('uuid');

        try {
            $this->deleteListingUseCase->handle($uuid);
        } catch (ListingNotFoundException $e) {
            return $this->responseFactory
                ->json(['message' => $e->getMessage()], $e->getCode());
        } catch (Throwable $e) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory
            ->json(['message' => 'Deleted'], Response::HTTP_OK);
    }

    public function patch(UpdateListingRequest $request): JsonResponse
    {
        $uuid = $request->string('uuid');
        $title = $request->string('title');
        $description = $request->string('description');

        try {
            $listing = $this->updateListingUseCase->handle($uuid, $title, $description);
        } catch (ListingNotFoundException $e) {
            return $this->responseFactory
                ->json(['message' => $e->getMessage()], $e->getCode());
        } catch (Throwable) {
            return $this->responseFactory
                ->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory
            ->json(['message' => 'Updated', 'id' => $listing->uuid], Response::HTTP_OK);
    }
}
