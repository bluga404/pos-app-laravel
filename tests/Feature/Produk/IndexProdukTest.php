<?php

namespace Tests\Feature\Produk;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class IndexProdukTest extends TestCase
{
    /**
     * @test
     */
    use RefreshDatabase;
    public function test(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('master-data.produk.index'));
        $response->assertStatus(200);
        $response->assertViewIs('produk.index');
    }
}
