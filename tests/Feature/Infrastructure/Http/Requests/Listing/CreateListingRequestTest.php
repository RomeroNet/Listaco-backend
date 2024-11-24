<?php

use App\Infrastructure\Http\Requests\Listing\CreateListingRequest;
use Faker\Factory;
use Illuminate\Validation\ValidationException;

covers(CreateListingRequest::class);

it('should validate the input', function (
    bool $hasTitle,
    bool $hasDescription,
    bool $descriptionIsNumeric,
    bool $isValid
) {
    $faker = Factory::create();

    $title = $hasTitle ? $faker->sentence : null;
    $description = $hasDescription ? $faker->paragraph : null;
    $description = $descriptionIsNumeric ? $faker->randomNumber() : $description;
    $data = [
        'title' => $title,
    ];

    if ($description) {
        $data['description'] = $description;
    }

    $request = new CreateListingRequest($data);

    try {
        $request->setContainer(app())
            ->setRedirector(app()->make('redirect'))
            ->validateResolved();
        $result = true;
    } catch (ValidationException) {
        $result = false;
    }

    expect($result)->toBe($isValid);
})->with([
    'when the title is present and there is no description' => [
        'hasTitle' => true,
        'hasDescription' => false,
        'descriptionIsNumeric' => false,
        'isValid' => true
    ],
    'when the title is not present and there is no description' => [
        'hasTitle' => false,
        'hasDescription' => false,
        'descriptionIsNumeric' => false,
        'isValid' => false
    ],
    'when the title is present and there is a description' => [
        'hasTitle' => true,
        'hasDescription' => true,
        'descriptionIsNumeric' => false,
        'isValid' => true
    ],
    'when the title is present and there is a description, but it is a number' => [
        'hasTitle' => true,
        'hasDescription' => true,
        'descriptionIsNumeric' => true,
        'isValid' => false
    ],
]);
