<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should register a user by its username, email, password', function () {
    $user = [
        'username' => 'noisyboy-9',
        'email' => 'sina.shariati@yahoo.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123'
    ];

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

    $user = [
        'email' => 'sina.shariati@yahoo.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123'
    ];

    $response = post('/api/register', $user);

    expect($response->status())->toBeInvalid();

    assertDatabaseMissing('users', [
        'email' => $user['email'],
    ]);
});

it('should encrypt user passwords before saving to DB', function () {
    $user = [
        'email' => 'sina.shariati@yahoo.com',
        'username' => 'noisyboy-9',
        'password' => 'admin123',
        'password_confirmation' => 'admin123'
    ];

    $response = post('/api/register', $user);
    expect($response->status())->toEqual(201);
    $storedUser = DB::table('users')->first();

    expect(Hash::check($user['password'], $storedUser->password))->toBeTrue();
});

it('should confirm password before registering', function () {
    withExceptionHandling();

    $user = [
        'email' => 'sina.shariati@yahoo.com',
        'username' => 'noisyboy-9',
        'password' => 'admin123'
    ];

    $response = post('/api/register', $user);

    expect($response->status())->toBeInvalid();

    assertDatabaseMissing('users', [
        'email' => $user['email'],
        'username' => $user['username']
    ]);
});

it('should not return password after registering', function () {
    withExceptionHandling();

    $user = [
        'email' => 'sina.shariati@yahoo.com',
        'username' => 'noisyboy-9',
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    $response = post('/api/register', $user);

    expect($response->content())
        ->json()
        ->data
        ->not->toHaveKey('password');
});

it('should use unique username for registration', function () {
    withExceptionHandling();

    $user1 = User::factory()->create();

    $user2 = [
        'email' => 'baduser@test.com',
        'username' => $user1->username, //not unique username
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    $response = post('/api/register', $user2);

    expect($response->status())->toEqual(302);

    assertDatabaseMissing('users', [
        'username' => $user2['username'],
        'email' => 'baduser@test.com'
    ]);
});

it('should use unique email for registration', function () {
    withExceptionHandling();

    $user1 = User::factory()->create();
    $user2 = [
        'email' => 'baduser@test.com',
        'username' => $user1->username, //not unique username
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    $response = post('/api/register', $user2);

    expect($response->status())->toEqual(302);

    assertDatabaseMissing('users', [
        'username' => $user2['username'],
        'email' => 'baduser@test.com'
    ]);
});

