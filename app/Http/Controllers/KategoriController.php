<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        confirmDelete('Hapus Data', 'Apakah anda yakin ingin menghapus data ini?');
        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {

        $id = $request->id;

        $request->validate(
            [
                'nama_kategori' => [
                    'required',
                    'string',
                    Rule::unique('kategoris', 'nama_kategori')->ignore($id),
                ],
                'deskripsi' => 'required|string|max:100',
            ],
            [
                'nama_kategori.required' => 'Nama Kategori wajib diisi.',
                'nama_kategori.unique' => 'Nama Kategori sudah ada di database.',
                'deskripsi.required' => 'Deskripsi wajib diisi.',
                'deskripsi.max' => 'Deskripsi maksimal 100 karakter.',
            ]
        );

        Kategori::updateOrCreate(
            ['id' => $id],
            [
                'nama_kategori' => $request->nama_kategori,
                'slug' => Str::slug($request->nama_kategori),
                'deskripsi' => $request->deskripsi,
            ]
            );

        toast()->success('Data Kategori berhasil disimpan.');
        return redirect()->route('master-data.kategori.index');
        // dd($request->all());
    }

    public function destroy($id){
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        toast()->success('Data Kategori berhasil dihapus.');
        return redirect()->route('master-data.kategori.index');
    }
}
