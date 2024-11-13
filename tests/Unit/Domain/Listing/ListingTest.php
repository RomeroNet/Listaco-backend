<?php

use App\Domain\Listing\Listing;

covers(
    Listing::class
);

it('should create a listing and convert it to array', function () {
    $faker = Faker\Factory::create();

    $uuid = $faker->uuid();
    $title = $faker->sentence();
    $description = $faker->paragraph();

    $listing = new Listing(
        $uuid,
        $title,
        $description
    );

    $expected = [
        'id' => $uuid,
        'title' => $title,
        'description' => $description
    ];

    expect($listing->toArray())->toBe($expected);
});
