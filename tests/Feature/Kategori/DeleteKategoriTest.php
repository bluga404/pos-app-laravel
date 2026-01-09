<?php

namespace Tests\Feature\Kategori;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Kategori;
use Tests\TestCase;

class DeleteKategoriTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function user_can_delete_kategori()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori makanan',
        ]);

        $response = $this->delete(route('master-data.kategori.destroy', $kategori->id));

        $response->assertRedirect(route('master-data.kategori.index'));

        $this->assertDatabaseMissing('kategoris', [
            'id' => $kategori->id
        ]);
    }

    /** @test */
    public function delete_kategori_returns_404_if_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete(route('master-data.kategori.destroy', 999));

        $response->assertStatus(404);
    }

}
