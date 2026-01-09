@extends('layouts.app')
@section('content-title', 'Pengeluaran Barang')
@section('content')

<form action="{{ route('pengeluaran-barang.store') }}" method="post" id="form-pengeluaran-barang">
@csrf
<x-alert :errors="$errors"/>
<div class="card">
    <div id="data-hidden"></div>
    <div class="d-flex align-items-center justify-content-between p-3 border">
        <h4 class="h5">Pengeluaran Barang</h4>
    </div>

    <div class="card-body">
        <div class="d-flex">
            <div class="w-100">
                <label for="">Produk</label>
                <select name="select2" id="select2" class="form-control"></select>
            </div>
            <div>
                <label for="">Stok</label>
                <input type="number" id="current_stok" class="form-control mx-1" style="width: 100px;" readonly>
            </div>
            <div>
                <label for="">Harga</label>
                <input type="number" id="harga_jual" class="form-control mx-1" style="width: 100px;" readonly>
            </div>
            <div>
                <label for="">Qty</label>
                <input type="number" id="qty" class="form-control mx-1" style="width: 100px;" min="1">
            </div>
            <div style="padding-top: 32px;">
                <button type="button" class="btn btn-dark" id="btn-add">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm" id="table-produk">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga Jual</th>
                            <th>Sub Total</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <label for="">Total</label>
                    <input type="number" class="form-control" id="total" readonly>
                </div>

                <div class="mt-2">
                    <label for="">Kembalian</label>
                    <input type="number" class="form-control" id="kembalian" readonly>
                </div>

                <div class="mt-2">
                    <label for="">Jumlah Bayar</label>
                    <input type="number" class="form-control" name="bayar" id="bayar" min="1">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

@endsection
@push('script')
<script>
    $(document).ready(function () {
        let selectedProduk = {};

        function hitungTotal(){
            let total = 0;

            $('#table-produk tbody tr').each(function(){
                const subTotal = parseInt($(this).find("td:eq(3)").text()) || 0;
                total += subTotal;
            });

            $('#total').val(total);
        }

        function hitungKembalian(){
            const total = Number($('#total').val()) || 0;
            const bayar = Number($('#bayar').val()) || 0;
            const kembalian = bayar - total;

            $('#kembalian').val(kembalian);
        }

        $('#select2').select2({
            theme: 'bootstrap',
            placeholder: 'Cari Produk...',
            ajax:{
                url:"{{ route('get-data.produk') }}",
                dataType: 'json',
                delay:250,
                data:(params) => {
                    return {
                        search:params.term
                    }
                },
                processResults:(data) => {
                    data.forEach(item => {
                        selectedProduk[item.id] = item;
                    })

                    return {
                        results:data.map(item=>{
                            return{
                                id:item.id,
                                text:item.nama_produk,
                            }
                        })
                    }
                },
                cache:true
            },
            minimumInputLength:2
        })

        $("#select2").on("change", function (e) {
            let id = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{ route('get-data.cek-stok') }}",
                data: {
                    id:id,
                },
                dataType: "json",
                success: function (response) { 
                    console.log(response);
                    $('#current_stok').val(response);
                    
                 }
            });
            $.ajax({
                type: "GET",
                url: "{{ route('get-data.cek-harga') }}",
                data: {
                    id:id,
                },
                dataType: "json",
                success: function (response) { 
                    console.log(response);
                    $('#harga_jual').val(response);
                    
                 }
            });
        });

        $('#btn-add').on('click', function(){
            const selectedId = $("#select2").val();
            const qty = Number($("#qty").val());
            const currentStok = Number($("#current_stok").val());
            const hargaJual = Number($("#harga_jual").val());
            const subTotal = parseInt(qty) * parseInt(hargaJual);

            if(!selectedId || !qty){
                alert('Harap pilih produk dan tentukan jumlah yang valid');
                return;
            }

            if(qty > currentStok){
                alert('Jumlah barang tidak tersedia');
                return;
            }

            let exist = false;

            $('#table-produk tbody tr').each(function(){
                const rowProduk = $(this).find("td:first").text();

                if(rowProduk === selectedProduk[selectedId].nama_produk){
                    let currentQty = parseInt($(this).find("td:eq(1)").text());
                    let newQty = currentQty + parseInt(qty);

                    $(this).find("td:eq(1)").text(newQty);
                    exist = true;
                    return false;
                }
            })

            if(!exist){
                // tambahkan data baru
                const row = `
                
                    <tr data-id="${selectedId}">
                        <td>${selectedProduk[selectedId].nama_produk}</td>
                        <td>${qty}</td>
                        <td>${hargaJual}</td>
                        <td>${subTotal}</td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                
                `
                $("#table-produk tbody").append(row);
            }

            $('#select2').val(null).trigger('change');
            $('#qty').val(null);
            $("#current_stok").val(null);
            $("#harga_jual").val(null);
            hitungTotal();
            hitungKembalian();
        });

        $("#table-produk").on("click", ".btn-remove", function () {
            $(this).closest("tr").remove();
            hitungTotal();
            hitungKembalian();
        });

        $('#form-pengeluaran-barang').on('submit', function () {
            $('#data-hidden').html("");

            $('#table-produk tbody tr').each(function(index, row){
                const namaProduk = $(row).find("td:eq(0)").text();
                const qty = $(row).find("td:eq(1)").text();
                const produkId = $(row).data("id");
                const hargaJual = $(row).find("td:eq(2)").text();
                const subTotal = $(row).find("td:eq(3)").text();

                const inputProduk = `<input type="hidden" name="produk[${index}][nama_produk]" value="${namaProduk}"> `;
                const inputQty = `<input type="hidden" name="produk[${index}][qty]" value="${qty}">`;
                const inputProdukId = `<input type="hidden" name="produk[${index}][produk_id]" value="${produkId}">`;
                const inputHargaJual = `<input type="hidden" name="produk[${index}][harga_jual]" value="${hargaJual}">`;
                const inputSubTotal = `<input type="hidden" name="produk[${index}][sub_total]" value="${subTotal}">`;

                $("#data-hidden").append(inputProduk).append(inputQty).append(inputProdukId).append(inputHargaJual).append(inputSubTotal);
            })
        });

        $("#bayar").on("input", function(){
            hitungKembalian();
        });

    });
</script>
@endpush