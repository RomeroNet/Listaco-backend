<?php

use App\Infrastructure\Http\Requests\Listing\UpdateListingRequest;
use Faker\Factory;
use Illuminate\Routing\Route;
use Illuminate\Validation\ValidationException;

covers(
    UpdateListingRequest::class
);

it('should validate the input', function (
    bool $hasUuid,
    bool $uuidIsValid,
    bool $hasTitle,
    bool $titleIsValid,
    bool $hasDescription,
    bool $descriptionIsValid,
    bool $shouldPass
) {
    $faker = Factory::create();

    $uuid = $hasUuid ? $faker->uuid : null;
    $uuid = $uuidIsValid ? $uuid : $faker->word;

    $title = $hasTitle ? $faker->sentence : null;
    $title = $titleIsValid ? $title : $faker->randomNumber();

    $description = $hasDescription ? $faker->sentence : null;
    $description = $descriptionIsValid ? $description : $faker->randomNumber();

    $request = new UpdateListingRequest(
        [
            'title' => $title,
            'description' => $description,
        ],
        [],
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

    expect($result)->toBe($shouldPass);
})->with([
    'when the request has no UUID' => [
        'hasUuid' => false,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'titleIsValid' => true,
        'hasDescription' => true,
        'descriptionIsValid' => true,
        'shouldPass' => false,
    ],
    'when the request has an invalid UUID' => [
        'hasUuid' => true,
        'uuidIsValid' => false,
        'hasTitle' => true,
        'titleIsValid' => true,
        'hasDescription' => true,
        'descriptionIsValid' => true,
        'shouldPass' => false,
    ],
    'when the request has a valid UUID but the title is empty' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => false,
        'titleIsValid' => true,
        'hasDescription' => true,
        'descriptionIsValid' => true,
        'shouldPass' => false,
    ],
    'when the request has a valid UUID and updates the title and description' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'titleIsValid' => true,
        'hasDescription' => true,
        'descriptionIsValid' => true,
        'shouldPass' => true,
    ],
    'when the request has a valid UUID but the title is invalid' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'titleIsValid' => false,
        'hasDescription' => true,
        'descriptionIsValid' => true,
        'shouldPass' => false,
    ],
    'when the request has a valid UUID but the description is invalid' => [
        'hasUuid' => true,
        'uuidIsValid' => true,
        'hasTitle' => true,
        'titleIsValid' => true,
        'hasDescription' => true,
        'descriptionIsValid' => false,
        'shouldPass' => false,
    ],
]);
