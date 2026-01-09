<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_create_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('users.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin@test.com',
        ]);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin@test.com',
            'name' => 'Admin Baru',
        ]);

        $user = User::where('email', 'admin@test.com')->first();
        $this->assertTrue(Hash::check('12345678', $user->password));
    }

    public function test_create_user_validation_error(): void
    {

        $admin = User::factory()->create(); 

        User::factory()->create([
            'email' => 'admin@test.com'
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'Admin Baru',
                'email' => 'admin@test.com',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_change_password_with_correct_old_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('passwordlama'),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('users.ganti-password'), [
            'old_password' => 'passwordlama',
            'new_password' => 'passwordbaru123',
            'new_password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertTrue(
            Hash::check('passwordbaru123', $user->fresh()->password)
        );
    }

    public function test_user_can_not_change_password_with_correct_old_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('passwordlama'),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('users.ganti-password'), [
            'old_password' => 'SALAH',
            'new_password' => 'passwordbaru123',
            'new_password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors('old_password');
    }

    public function test_user_can_not_change_password_with_wrong_new_password_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('users.ganti-password'), [
            'old_password' => 'password',
            'new_password' => 'passwordbaru123',
            'new_password_confirmation' => 'beda',
        ]);

        $response->assertSessionHasErrors('new_password');
    }
}
