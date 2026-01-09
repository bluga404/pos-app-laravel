<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DeleteUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_can_delete_user(): void
    {
        $authUser = User::factory()->create();

        $this->actingAs($authUser);

        $this->post(route('users.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin@test.com',
        ]);

        $targetUser = User::where('email', 'admin@test.com')->first();
        $this->assertNotNull($targetUser);

        $this->delete(route('users.destroy', $targetUser->id))
            ->assertStatus(302);

        $this->assertDatabaseMissing('users', [
            'email' => 'admin@test.com',
        ]);
    }
}
