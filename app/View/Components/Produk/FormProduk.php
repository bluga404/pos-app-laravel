<?php

namespace App\View\Components\Produk;

use App\Models\Kategori;
use App\Models\Produk;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormProduk extends Component
{
    /**
     * Create a new component instance.
     */
    public $id, $nama_produk, $harga_jual, $harga_beli_pokok, $stok, $stok_minimal, $is_active, $kategori_id, $kategori;
    public function __construct($id = null)
    {
        $this->kategori = Kategori::all();
        if ($id){
            $produk = Produk::find($id);
            $this->id = $produk->id;
            $this->nama_produk = $produk->nama_produk;
            $this->harga_jual = $produk->harga_jual;
            $this->harga_beli_pokok = $produk->harga_beli_pokok;
            $this->stok = $produk->stok;
            $this->stok_minimal = $produk->stok_minimal;
            $this->is_active = $produk->is_active;
            $this->kategori_id = $produk->kategori_id;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.produk.form-produk');
    }
}
