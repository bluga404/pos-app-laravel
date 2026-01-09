<?php

namespace Tests\Feature\Produk;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;

class UpdateProdukTest extends TestCase
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
            'nama_produk'      => 'Produk Lama',
            'sku'              => 'SKU-00001',
            'harga_jual'       => 10000,
            'harga_beli_pokok' => 8000,
            'kategori_id'      => $kategoriId,
            'stok'             => 10,
            'stok_minimal'     => 2,
            'is_active'        => false,
        ]);
    }

    /** @test */
    public function user_can_update_existing_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = $this->createKategori();
        $produk = $this->createProduk($kategori->id);

        $response = $this->post(route('master-data.produk.store'), [
            'id'                => $produk->id,
            'nama_produk'       => 'Produk Update',
            'harga_jual'        => 12000,
            'harga_beli_pokok'  => 9000,
            'kategori_id'       => $kategori->id,
            'stok'              => 15,
            'stok_minimal'      => 3,
            'is_active'         => true,
        ]);

        $response->assertRedirect(route('master-data.produk.index'));

        $this->assertDatabaseHas('produks', [
            'id'          => $produk->id,
            'nama_produk' => 'Produk Update',
            'is_active'   => true,
        ]);
    }

    /** @test */
    public function sku_does_not_change_when_product_is_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = $this->createKategori();
        $produk = $this->createProduk($kategori->id);

        $this->post(route('master-data.produk.store'), [
            'id'                => $produk->id,
            'nama_produk'       => 'Produk Baru',
            'harga_jual'        => 15000,
            'harga_beli_pokok'  => 10000,
            'kategori_id'       => $kategori->id,
            'stok'              => 20,
            'stok_minimal'      => 5,
            'is_active'         => true,
        ]);

        $produk->refresh();

        $this->assertEquals('SKU-00001', $produk->sku);
    }

    /** @test */
    public function update_passes_when_nama_produk_is_same_as_itself()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = $this->createKategori();

        $produk = $this->createProduk($kategori->id);

        $response = $this->post(route('master-data.produk.store'), [
            'id'                => $produk->id,
            'nama_produk'       => 'Produk Lama', // same
            'harga_jual'        => 11000,
            'harga_beli_pokok'  => 9000,
            'kategori_id'       => $kategori->id,
            'stok'              => 12,
            'stok_minimal'      => 2,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('master-data.produk.index'));
    }

    /** @test */
    public function update_fails_when_harga_jual_is_invalid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $kategori = $this->createKategori();
        $produk = $this->createProduk($kategori->id);

        $response = $this->post(route('master-data.produk.store'), [
            'id'                => $produk->id,
            'nama_produk'       => 'Produk Error',
            'harga_jual'        => -100,
            'harga_beli_pokok'  => 9000,
            'kategori_id'       => $kategori->id,
            'stok'              => 10,
            'stok_minimal'      => 2,
        ]);

        $response->assertSessionHasErrors('harga_jual');
    }
}
