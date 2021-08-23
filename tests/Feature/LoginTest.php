<?php

use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should be able to login with email and password', function () {
    $credentials = [
        'email' => addNewUser()->email,
        'password' => 'password'
    ];

    $response = post('/api/login', $credentials);

    expect($response->content())
        ->json()
        ->message->toEqual('Logged in successfully')
        ->token->not->toBeEmpty()
        ->and($response->status())
        ->toEqual(200);
});

it('should return the api_token stored in the database', function () {
    $user = addNewUser();

    $credentials = [
        'email' => $user->email,
        'password' => 'password'
    ];

    $response = post('/api/login', $credentials);

    expect($response->content())
        ->json()
        ->token->toEqual($user->api_token);
});
