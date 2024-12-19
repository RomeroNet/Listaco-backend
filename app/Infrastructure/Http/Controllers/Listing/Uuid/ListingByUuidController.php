<?php

namespace App\Infrastructure\Http\Controllers\Listing\Uuid;

use App\Application\UseCase\Listing\DeleteListingUseCase;
use App\Application\UseCase\Listing\GetListingByUuidUseCase;
use App\Application\UseCase\Listing\UpdateListingUseCase;
use App\Domain\Listing\ListingNotFoundException;
use App\Infrastructure\Http\Requests\Listing\DeleteListingRequest;
use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ListingByUuidController extends Controller
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly GetListingByUuidUseCase $getListingByUuidUseCase,
        private readonly DeleteListingUseCase $deleteListingUseCase,
        private readonly UpdateListingUseCase $updateListingUseCase
    ) {
    }

    public function get(GetListingRequest $request): JsonResponse
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

    public function delete(DeleteListingRequest $request): JsonResponse
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
        $description = $request->has('description')
            ? $request->string('description')
            : null;

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
