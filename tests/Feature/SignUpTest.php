<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should register a user by its username, email, password', function () {
    $user = scaffoldNewUser();

    $response = post("/api/register", $user);

    expect($response->content())
        ->json()
        ->message->toEqual("User registered successfully")
        ->data->toBeArray()
        ->toHaveKey('username', $user['username'])
        ->toHaveKey('email', $user['email'])
        ->and($response->status())->toEqual(201);


    assertDatabaseHas('users', [
        'username' => $user['username'],
        'email' => $user['email']
    ]);
});

it('should have a username and email for registration', function () {
    withExceptionHandling();

    $user = scaffoldNewUser(['username' => '']);

    $response = post('/api/register', $user);

    expect($response->status())->toBeInvalid();

    assertDatabaseMissing('users', [
        'email' => $user['email'],
    ]);
});

it('should encrypt user passwords before saving to DB', function () {
    $user = scaffoldNewUser();

    $response = post('/api/register', $user);
    expect($response->status())->toEqual(201);
    $storedUser = DB::table('users')->first();

    expect(Hash::check($user['password'], $storedUser->password))->toBeTrue();
});

it('should confirm password before registering', function () {
    withExceptionHandling();

    $user = scaffoldNewUser(['password' => '']);

    $response = post('/api/register', $user);

    expect($response->status())->toBeInvalid();

    assertDatabaseMissing('users', [
        'email' => $user['email'],
        'username' => $user['username']
    ]);
});

it('should not return password after registering', function () {
    withExceptionHandling();

    $user = scaffoldNewUser();
    $response = post('/api/register', $user);

    expect($response->content())
        ->json()
        ->data
        ->not->toHaveKey('password');
});

it('should use unique username for registration', function () {
    withExceptionHandling();

    $user1 = addNewUser();
    $user2 = scaffoldNewUser(['username' => $user1->username]);

    $response = post('/api/register', $user2);

    expect($response->status())->toEqual(302);

    assertDatabaseMissing('users', [
        'username' => $user2['username'],
        'email' => 'baduser@test.com'
    ]);
});

it('should use unique email for registration', function () {
    withExceptionHandling();

    $user1 = addNewUser();
    $user2 = scaffoldNewUser(['email' => $user1->email]);

    $response = post('/api/register', $user2);

    expect($response->status())->toEqual(302);

    assertDatabaseMissing('users', [
        'username' => $user2['username'],
        'email' => 'baduser@test.com'
    ]);
});

it('should create a random api_token after registration', function () {
    addNewUser();

    expect(DB::table('users')->first(['api_token']))
        ->not->toBeNull();
});
