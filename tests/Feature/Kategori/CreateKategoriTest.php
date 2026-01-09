<?php

namespace Tests\Feature\Kategori;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateKategoriTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function user_can_create_kategori_with_valid_data()
    {

        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);
        $this->actingAs($user);

        $response = $this->post(route('master-data.kategori.store'), [
            'nama_kategori' => 'Elektronik',
            'deskripsi' => 'Kategori barang elektronik',
        ]);

        $response->assertRedirect(route('master-data.kategori.index'));

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Elektronik',
            'slug' => Str::slug('Elektronik'),
            'deskripsi' => 'Kategori barang elektronik',
        ]);
    }

    /** @test */
    public function create_kategori_fails_if_nama_kategori_is_empty()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('master-data.kategori.store'), [
            'nama_kategori' => '',
            'deskripsi' => 'Deskripsi valid',
        ]);

        $response->assertSessionHasErrors('nama_kategori');
    }

    /** @test */
    public function create_kategori_fails_if_nama_kategori_is_duplicate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori makanan',
        ]);

        $response = $this->post(route('master-data.kategori.store'), [
            'nama_kategori' => 'Makanan',
            'deskripsi' => 'Kategori makanan lain',
        ]);

        $response->assertSessionHasErrors('nama_kategori');
    }
}
