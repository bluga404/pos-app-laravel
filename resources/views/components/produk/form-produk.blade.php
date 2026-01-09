<div>
    <button type="button" class="btn {{ $id ? 'btn-warning' : 'btn-primary' }}" data-toggle="modal"
        data-target="#formProduk{{ $id ?? '' }}">
        @if ($id)
        <i class="fas fa-edit"></i>
        @else
        Produk Baru
        @endif
    </button>
    <div class="modal fade" id="formProduk{{ $id ?? '' }}">
        <form action="{{ route('master-data.produk.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $id ?? '' }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $id ? 'Form Edit Produk' : 'Form Produk Baru' }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group my-1">
                            <label for="">Nama Product</label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control"
                                value="{{ $nama_produk }}">
                        </div>

                        <div class="form-group my-1">
                            <label for="">Kategori Produk</label>
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}" {{ $kategori_id || old('kategori_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group my-1">
                            <label for="">Harga Jual</label>
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control" value="{{ $id ? $harga_jual : old('harga_jual') }}">
                        </div>

                        <div class="form-group my-1">
                            <label for="">Harga Beli Pokok</label>
                            <input type="number" name="harga_beli_pokok" id="harga_beli_pokok" class="form-control" value="{{ $id ? $harga_beli_pokok : old('harga_beli_pokok') }}">
                        </div>

                        <div class="form-group my-1">
                            <label for="">Stok Persediaan</label>
                            <input type="number" name="stok" id="stok" class="form-control" value="{{ $id ? $stok : old('stok') }}">
                        </div>

                        <div class="form-group my-1">
                            <label for="">Stok Minimal</label>
                            <input type="number" name="stok_minimal" id="stok_minimal" class="form-control" value="{{ $id ? $stok_minimal : old('stok_minimal') }}">
                        </div>

                        <div class="form-group my-1 d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $id ? $is_active : false) ? 'checked' : '' }} class="mb-2">
                                <label for="" class="ml-2">Produk Aktif?</label>
                            </div>
                            <small class="text-secondary">Jika Aktif, maka produk akan ditampilkan di halaman kasir</small>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!-- /.modal -->
</div>