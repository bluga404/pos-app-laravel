<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UpdateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_can_update_user_name_and_email(): void
    {
        $authUser = User::factory()->create();

        $this->actingAs($authUser);

        $this->post(route('users.store'), [
            'name' => 'Admin Lama',
            'email' => 'admin@test.com',
        ]);

        $response = $this->post(route('users.store'), [
            'name'  => 'Admin Baru',
            'email' => 'new@email.com',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name'  => 'Admin Baru',
            'email' => 'new@email.com',
        ]);

    }
}
