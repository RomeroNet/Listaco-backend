<?php

use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Faker\Factory;
use Illuminate\Routing\Route;
use Illuminate\Validation\ValidationException;

it('should validate the input', function (
    bool $hasUuid,
    bool $uuidIsValid,
    bool $hasTitle,
    bool $hasDescription,
) {
    $faker = Factory::create();

    $uuid = $hasUuid ? $faker->uuid : null;
    $uuid = $uuidIsValid ? $uuid : $faker->word;

    $title = $hasTitle ? $faker->sentence : null;
    $description = $hasDescription ? $faker->sentence : null;

    $request = new UpdateListingRequest(
        [],
        [
            'title' => $title,
            'description' => $description,
        ],
        [],
        [],
        [],
        ['REQUEST_URI' => "/api/listing/{$uuid}"]
    );

    try {
        $request->setContainer(app())
            ->setRedirector(app()->make('redirect'))
            ->setRouteResolver(fn() => new Route('PATCH', '/api/listing/{uuid}', [])->bind($request))
            ->validateResolved();
        $result = true;
    } catch (ValidationException) {
        $result = false;
    }

    expect($result)->toBe($uuidIsValid);
})->with([
    'when the request has no UUID' => [
        'hasUuid' => false,
        'uuidIsValid' => false,
        'hasTitle' => true,
        'hasDescription' => true,
    ],
    'when the request has an invalid UUID' => [
        'hasUuid' => true,
        'uuidIsValid' => false,
        'hasTitle' => true,
        'hasDescription' => true,
    ],
    'when the request has a valid UUID but does not update anything' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => false,
        'hasDescription' => false,
    ],
    'when the request has a valid UUID and updates the title' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'hasDescription' => false,
    ],
    'when the request has a valid UUID and updates the description' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => false,
        'hasDescription' => true,
    ],
    'when the request has a valid UUID and updates the title and description' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'hasDescription' => true,
    ],
]);
