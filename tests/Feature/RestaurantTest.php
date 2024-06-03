<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_restaurants_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/restaurantes');
        $response->assertStatus(200);
        $response->assertSee('Restaurantes');
    }

    /** @test */
    public function it_fetches_all_restaurants()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/restaurantes/getAll');
        $response->assertStatus(200);
        // Add assertions for the response content if necessary
    }

    
}
