@extends('layouts.app')
@section('content-title', 'Data Produk')
@section('content')

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title mt-2">Data Produk</h4>
            <div class="d-flex justify-content-end">
                <x-produk.form-produk />
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <x-alert :errors="$errors" />
                <table class="table table-bordered table-striped w-100" id="table2">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th>Harga Beli Pokok</th>
                            <th>Stok</th>
                            <th>Aktif</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>Rp. {{ number_format($item->harga_jual) }}</td>
                                <td>Rp. {{ number_format($item->harga_beli_pokok) }}</td>
                                <td>{{ number_format($item->stok) }}</td>
                                <td>
                                    <p class="badge {{ $item->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <x-produk.form-produk :id="$item->id" />
                                        <a href="{{ route('master-data.produk.destroy', $item->id) }}" data-confirm-delete="true"
                                            class="btn btn-danger ml-2">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection