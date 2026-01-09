@extends('layouts.app')

@section('content-title', 'Dashboard')
@section('content')



<div class="row">
    <x-dashboard-card type="bg-success" icon="fas fa-money-bill-alt" label="Total Pendapatan {{ $namaBulanIni }} {{ $tahunIni }}" value="{{ $totalPendapatan }}" />
    <x-dashboard-card type="bg-warning" icon="fas fa-shopping-cart" label="Total Order" value="{{ $totalOrder }}" />
    <x-dashboard-card type="bg-info" icon="fas fa-shopping-bag" label="Total Produk" value="{{ $totalProduk }}" />
    <x-dashboard-card type="bg-orange" icon="fas fa-users" label="Total User" value="{{ $totalUsers }}" />
</div>
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transaksi Terakhir</div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <th>Nomor Transaksi</th>
                            <th>Jumlah Item</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $item)
                        <tr>
                            <td>{{ $item->tanggal_transaksi }}</td>
                            <td>{{ $item->nomor_pengeluaran }}</td>
                            <td>{{ $item->items->sum('qty') }}</td>
                            <td>Rp. {{ number_format($item->total_harga) }}</td>
                        </tr>                                       
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                5 Data Transaksi Terakhir
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transaksi Terakhir</div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produkTerlaris as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->total_terjual }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                5 Data Produk Terlaris
            </div>
        </div>
    </div>
</div>
@endsection