<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'message',
        'access_token',
        'token_type',
        'user',
    ]);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});

test('users can not authenticate with non existing user', function () {
    $this->postJson('/api/v1/auth/login', [
        'email' => 'non-existing@example.com',
        'password' => 'password',
    ])->assertUnauthorized();
});

test('login requires email and password', function () {
    $this->postJson('/api/v1/auth/login', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});
