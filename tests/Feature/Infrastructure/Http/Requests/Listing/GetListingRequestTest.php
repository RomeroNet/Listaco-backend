<?php

use App\Infrastructure\Http\Requests\Listing\GetListingRequest;
use Faker\Factory;
use Illuminate\Routing\Route;
use Illuminate\Validation\ValidationException;

covers(
    GetListingRequest::class
);

it('should validate the input', function (
    bool $hasUuid,
    bool $uuidIsValid,
) {
    $faker = Factory::create();

    $uuid = $hasUuid ? $faker->uuid : null;
    $uuid = $uuidIsValid ? $uuid : $faker->word;

    $request = new GetListingRequest([], [], [], [], [], ['REQUEST_URI' => "/api/listing/{$uuid}"]);

    try {
        $request->setContainer(app())
            ->setRedirector(app()->make('redirect'))
            ->setRouteResolver(fn() => new Route('GET', '/api/listing/{uuid}', [])->bind($request))
            ->validateResolved();
        $result = true;
    } catch (ValidationException) {
        $result = false;
    }

    expect($result)->toBe($uuidIsValid);
})->with([
    'when the request has a valid UUID' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
    ],
    'when the request has an invalid UUID' => [
        'hasUuid' => true,
        'uuidIsValid' => false,
    ],
    'when the request does not have a UUID' => [
        'hasUuid' => false,
        'uuidIsValid' => false,
    ],
]);
