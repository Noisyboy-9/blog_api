<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Testing\TestResponse;

uses(Tests\TestCase::class)->in('Feature');

// custom expects
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});


//helpers
function GET(string $url): TestResponse
{
    return test()->get($url);
}

function POST(string $url, array $data): TestResponse
{
    return test()->post($url, $data);
}

function DELETE(string $url): TestResponse
{
    return test()->delete($url);
}

function PATCH(string $url, array $data): TestResponse
{
    return test()->delete($url, $data);
}

function PUT(string $url, array $data): TestResponse
{
    return test()->put($url, $data);
}

