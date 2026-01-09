<?php

namespace Tests\Feature\Kategori;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateKategoriTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function user_can_update_kategori(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori lama',
        ]);

        $response = $this->post(route('master-data.kategori.store'), [
            'id' => $kategori->id,
            'nama_kategori' => 'Makanan Baru',
            'deskripsi' => 'Kategori baru',
        ]);

        $response->assertRedirect(route('master-data.kategori.index'));

        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori->id,
            'nama_kategori' => 'Makanan Baru',
            'slug' => Str::slug('Makanan Baru'),
            'deskripsi' => 'Kategori baru',
        ]);
    }

    /** @test */
    public function update_kategori_fails_if_nama_kategori_is_duplicate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori makanan',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Minuman',
            'slug' => 'minuman',
            'deskripsi' => 'Kategori minuman',
        ]);

        $response = $this->post(route('master-data.kategori.store'), [
            'id' => $kategori->id,
            'nama_kategori' => 'Makanan',
            'deskripsi' => 'Coba duplikat',
        ]);

        $response->assertSessionHasErrors('nama_kategori');
    }

    /** @test */
    public function update_kategori_succeeds_if_nama_kategori_is_same_as_itself()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = Kategori::create([
            'nama_kategori' => 'Fashion',
            'slug' => 'fashion',
            'deskripsi' => 'Kategori fashion',
        ]);

        $response = $this->post(route('master-data.kategori.store'), [
            'id' => $kategori->id,
            'nama_kategori' => 'Fashion',
            'deskripsi' => 'Deskripsi diperbarui',
        ]);

        $response->assertRedirect(route('master-data.kategori.index'));

        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori->id,
            'nama_kategori' => 'Fashion',
            'deskripsi' => 'Deskripsi diperbarui',
        ]);
    }
}
