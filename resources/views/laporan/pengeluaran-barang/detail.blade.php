@extends('layouts.app')
@section('content-title', 'Invoice Pengeluaran Barang')
@section('content')

    <div class="card">
        <div class="d-flex justify-content-between algn-items-center p-3">
            <div>
                <h3 class="h3">PT POS APP</h3>
                <h4 class="h6">Invoice Transaksi Barang</h4>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Nama Petugas</h6>
                        <p>{{ $data->nama_petugas }}</p>
                    </div>
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Tanggal Transaksi</h6>
                        <p>{{ $data->tanggal_pengeluaran }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Nomor Transaksi</h6>
                        <p>{{ $data->nomor_pengeluaran }}</p>
                    </div>
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Total Item</h6>
                        <p>{{ $data->items->sum('qty') }} produk</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Jumlah bayar</h6>
                        <p>Rp. {{ number_format($data->bayar) }}</p>
                    </div>
                    <div class="d-flex align-content-center">
                        <h6 class="text-bold w-25 mt-1">Kembalian</h6>
                        <p>Rp. {{ number_format($data->kembalian) }}</p>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20px" class="text-center">No</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga Jual</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->items as $index => $item)
                            <tr>
                                <td style="width: 20px" class="text-center">{{ $index+1 }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ number_format($item->qty) }} <small>pcs</small></td>
                                <td>Rp. {{ number_format($item->harga_jual) }}</td>
                                <td>Rp. {{ number_format($item->sub_total) }} </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-bold text-right">Total Transaksi</td>
                            <td class="text-bold">Rp. {{ number_format($data->total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection