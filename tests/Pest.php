<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

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

