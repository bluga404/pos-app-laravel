<?php

namespace App\Http\Controllers;

use App\Models\ItemPengeluaranBarang;
use App\Models\PengeluaranBarang;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        return view('pengeluaran-barang.index');
    }

    public function store(Request $request)
    {

        if (empty($request->produk)) {
            toast()->error('Tidak ada produk yang ditambahkan');
            return redirect()->back();
        }

        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar' => 'required|numeric|min:1',
        ], [
            'produk.required' => 'Produk harus diisi',
            'bayar.required' => 'Bayar harus diisi',
        ]);

        $produk = collect($request->produk);
        $bayar = $request->bayar;
        $total = $produk->sum('sub_total');
        $kembalian = intval($bayar) - intval($total);

        if ($bayar < $total) {
            toast()->error('Jumlah bayar tidak mencukupi');
            return redirect()->back()->withInput([
                'produk' => $produk,
                'bayar' => $bayar,
                'total' => $total,
                'kembalian' => $kembalian,
            ]);
        }

        $newData = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomorPengeluaran(),
            'nama_petugas' => Auth::user()->name,
            'total_harga' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
        ]);

        foreach ($produk as $item) {
            ItemPengeluaranBarang::create([
                'nomor_pengeluaran' => $newData->nomor_pengeluaran,
                'nama_produk' => $item['nama_produk'],
                'qty' => $item['qty'],
                'harga_jual' => $item['harga_jual'],
                'sub_total' => $item['sub_total'],
            ]);

            Produk::where('id', $item['produk_id'])->decrement('stok', $item['qty']);
        }

        toast()->success('Transaksi Tersimpan');
        return redirect()->route('pengeluaran-barang.index');
    }

    public function laporan()
    {
        $pengeluaranBarang = PengeluaranBarang::orderBy('created_at', 'desc')->get()->map(function($item){
            $item->tanggal_pengeluaran = Carbon::parse($item->created_at)->locale('id')->translatedFormat('l, d F Y');
            return $item;
        });
        return view('laporan.pengeluaran-barang.laporan', compact('pengeluaranBarang'));
    }

    public function detailLaporan(String $nomor_pengeluaran){
        $data = PengeluaranBarang::with('items')->where('nomor_pengeluaran', $nomor_pengeluaran)->first();
        $data->tanggal_pengeluaran = Carbon::parse($data->created_at)->locale('id')->translatedFormat('l, d F Y');
        $data->total = $data->items->sum('sub_total');
        return view('laporan.pengeluaran-barang.detail', compact('data'));
    }
}
