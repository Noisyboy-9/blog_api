<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

// custom expects
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeInvalid', function () {
    return $this->toEqual(302);
});

