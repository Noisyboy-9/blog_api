<?php

use Illuminate\Validation\UnauthorizedException;
use function Pest\Laravel\getJson;

it("doesn't need to be logged in to be able to get the feed", function () {
    addNewPost();
    addNewPost();

    expect(getJson('/api/feed'))->not()->toThrow(UnauthorizedException::class);
});
