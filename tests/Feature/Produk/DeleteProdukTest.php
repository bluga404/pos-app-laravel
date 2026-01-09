<?php

namespace Tests\Feature\Produk;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteProdukTest extends TestCase
{
    /**
     * setup
     */
    use RefreshDatabase;
    protected function createKategori()
    {
        return Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori makanan',
        ]);
    }

    protected function createProduk($kategoriId)
    {
        return Produk::create([
            'nama_produk'      => 'Produk Test',
            'sku'              => 'SKU-00001',
            'harga_jual'       => 10000,
            'harga_beli_pokok' => 8000,
            'kategori_id'      => $kategoriId,
            'stok'             => 10,
            'stok_minimal'     => 2,
            'is_active'        => true,
        ]);
    }

    #[Test]
    public function user_can_delete_product(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = $this->createKategori();
        $produk = $this->createProduk($kategori->id);

        $response = $this->delete(
            route('master-data.produk.destroy', $produk->id)
        );

        $response->assertRedirect(route('master-data.produk.index'));

        $this->assertDatabaseMissing('produks', [
            'id' => $produk->id,
        ]);
    }
}
