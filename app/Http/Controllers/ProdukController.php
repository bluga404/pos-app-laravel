<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        confirmDelete('Hapus Data', 'Apakah anda yakin ingin menghapus data ini?');
        return view('produk.index', compact('produk'));
    }

    public function store(Request $request)
    {
        $id = $request->id;
        // dd($request->all());
        $request->validate([
            'nama_produk' => [
                    'required',
                    'string',
                    Rule::unique('produks', 'nama_produk')->ignore($id),
                ],
            'harga_jual'       => 'required|numeric|min:0',
            'harga_beli_pokok' => 'required|numeric|min:0',
            'kategori_id'      => 'required|exists:kategoris,id',
            'stok'             => 'required|numeric|min:0',
            'stok_minimal'     => 'required|numeric|min:0',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi',
            'nama_produk.unique'   => 'Nama produk sudah ada',

            'harga_jual.required' => 'Harga jual harus diisi',
            'harga_jual.numeric'  => 'Harga jual harus berupa angka',
            'harga_jual.min'      => 'Harga jual minimal 0',

            'harga_beli_pokok.required' => 'Harga beli pokok harus diisi',
            'harga_beli_pokok.numeric'  => 'Harga beli pokok harus berupa angka',
            'harga_beli_pokok.min'      => 'Harga beli pokok minimal 0',

            'kategori_id.required' => 'Kategori harus diisi',
            'kategori_id.exists'   => 'Kategori tidak valid',

            'stok.required' => 'Stok harus diisi',
            'stok.numeric'  => 'Stok harus berupa angka',
            'stok.min'      => 'Stok minimal 0',

            'stok_minimal.required' => 'Stok minimal harus diisi',
            'stok_minimal.numeric'  => 'Stok minimal harus berupa angka',
            'stok_minimal.min'      => 'Stok minimal minimal 0',
        ]);

        $newRequest = [
                'id' => $id,
                'nama_produk'   => $request->nama_produk,
                'harga_jual'    => $request->harga_jual,
                'harga_beli_pokok'    => $request->harga_beli_pokok,
                'kategori_id'    => $request->kategori_id,
                'stok'    => $request->stok,
                'stok_minimal'    => $request->stok_minimal,
                'is_active'    => $request->is_active ? true : false,
        ];

        if (!$id){
            $newRequest['sku'] = Produk::nomorSku();
        }

        Produk::updateOrCreate(
            ["id" => $id],
            $newRequest,
        );

        toast()->success('Berhasil menyimpan data produk');
        return redirect()->route('master-data.produk.index');
    }

    public function destroy($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();
        toast()->success('Data Produk berhasil dihapus.');
        return redirect()->route('master-data.produk.index');
    }

    public function getData(){
        $search = request()->query('search');
        $query = Produk::query();
        $produk = $query->where('nama_produk', 'like', '%' . $search . '%')->get();
        return response()->json($produk);
    }

    public function cekStok(){
        $id = request()->query('id');
        $stok = Produk::find($id)->stok;
        return response()->json($stok);
    }

    public function cekHarga(){
        $id = request()->query('id');
        $harga = Produk::find($id)->harga_jual;
        return response()->json($harga);
    }

    // dd($request->all());
}
