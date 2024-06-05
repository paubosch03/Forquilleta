<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->beforeEach(function () {
        $this->user = User::factory()->create();
    })
    ->group('restaurants');

it('displays the restaurants page', function () {
    $response = $this->actingAs($this->user)->get('/restaurantes');

    expect($response->status())->toBe(200);
    expect($response->content())->toContain('Restaurantes');
});

it('fetches all restaurants', function () {
    $response = $this->actingAs($this->user)->get('/restaurantes/getAll');

    expect($response->status())->toBe(200);
    // Add more expectations as necessary for response content
});