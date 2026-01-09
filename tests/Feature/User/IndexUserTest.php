<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class IndexUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_index_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }
}
