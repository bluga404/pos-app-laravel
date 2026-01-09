<?php

namespace Tests\Feature\Produk;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kategori;

class CreateProdukTest extends TestCase
{
    /**
     * @test
     */
    use RefreshDatabase;
    protected function validPayload(array $override = [])
    {

        $kategori = Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug' => 'makanan',
            'deskripsi' => 'Kategori makanan',
        ]);

        return array_merge([
            'nama_produk' => 'Produk Valid',
            'harga_jual' => 10000,
            'harga_beli_pokok' => 8000,
            'kategori_id' => $kategori->id,
            'stok' => 50,
            'stok_minimal' => 2,
        ], $override);
    }

    /** @test */
    public function store_fails_when_name_produk_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload([
                'nama_produk' => '',
            ])
        );

        $response->assertSessionHasErrors('nama_produk');
    }

    /** @test */
    public function store_fails_when_nama_produk_is_not_unique()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = $this->validPayload();

        $this->post(route('master-data.produk.store'), $payload);

        $response = $this->post(route('master-data.produk.store'), $payload);

        $response->assertSessionHasErrors('nama_produk');
    }

    /** @test */
    public function store_fails_when_harga_jual_is_not_numeric()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = $this->validPayload();
        ;

        $response = $this->post(route('master-data.produk.store'), $this->validPayload([
            'harga_jual' => 'not-numeric',
        ]));

        $response->assertSessionHasErrors('harga_jual');
    }

    /** @test */
    public function store_fails_when_harga_jual_is_less_than_zero()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload([
                'harga_jual' => -1,
            ])
        );

        $response->assertSessionHasErrors('harga_jual');
    }

    /** @test */
    public function store_fails_when_kategori_id_is_invalid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload([
                'kategori_id' => 9999,
            ])
        );

        $response->assertSessionHasErrors('kategori_id');
    }

    /** @test */
    public function store_fails_when_stok_is_negative()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload([
                'stok' => -5,
            ])
        );

        $response->assertSessionHasErrors('stok');
    }

    /** @test */
    public function store_fails_when_stok_minimal_is_not_numeric()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload([
                'stok_minimal' => 'abc',
            ])
        );

        $response->assertSessionHasErrors('stok_minimal');
    }

    /** @test */
    public function store_product_passes_when_all_fields_are_valid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            route('master-data.produk.store'),
            $this->validPayload()
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('master-data.produk.index'));
    }
}
