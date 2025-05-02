<?php

use App\Framework\Infrastructure\Http\Controller\Index;
use function Pest\Laravel\getJson;

covers(Index::class);

const EXPECTED_CONTENT = ['status' => 'RomeroNet Boilerplate :)'];

it('should return some content', function () {
    getJson('/api')
        ->assertContent(json_encode(EXPECTED_CONTENT))
        ->assertStatus(200);
});
