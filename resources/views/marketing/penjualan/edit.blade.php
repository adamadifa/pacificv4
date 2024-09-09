@extends('layouts.app')
@section('titlepage', 'Edit Penjualan')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Penjualan</span> / <span>Edit Penjualan</span>
@endsection
<form action="{{ route('penjualan.update', Crypt::encrypt($penjualan->no_faktur)) }}" method="POST" id="formPenjualan">
    @csrf
    @method('PUT')
    <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
    <input type="hidden" name="max_kredit" id="max_kredit">
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Faktur" name="no_faktur" icon="ti ti-barcode" value="{{ $penjualan->no_faktur }}" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
                                value="{{ $penjualan->tanggal }}" />
                            <x-input-with-icon label="Pelanggan" name="nama_pelanggan" icon="ti ti-user" readonly="true" />
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                            <x-input-with-icon label="Salesman" name="nama_salesman" icon="ti ti-user" readonly="true" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman">
                            <div class="form-group mb-3">
                                <textarea name="keterangan" class="form-control" id="" cols="30" rows="5" id="keterangan" placeholder="Keterangan">{{ $penjualan->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="../../assets/img/elements/2.jpg" alt="Card image cap" style="height:250px; object-fit:cover"
                            id="foto">
                        <div class="card-body">
                            <p class="card-text" id="alamat_pelanggan">

                            </p>
                            <table class="table">
                                <tr>
                                    <th style="width: 60%">No. HP</th>
                                    <td id="no_hp_pelanggan" style="width: 40%"></td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td id="latitude"></td>
                                </tr>
                                <tr>
                                    <th>Longitude</th>
                                    <td id="longitude"></td>
                                </tr>
                                <tr>
                                    <th>Limit</th>
                                    <td id="limit_pelanggan_text"></td>
                                </tr>
                                <tr>
                                    <th>Piutang</th>
                                    <td id="sisa_piutang_text"></td>
                                </tr>
                                <tr>
                                    <th>Faktur Kredit</th>
                                    <td id="jmlfaktur_kredit"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="icon-cart mt-3">
                                    <i class="ti ti-shopping-bag text-primary" style="font-size: 8rem"></i>
                                </div>
                                <div class="mt-2">
                                    <h1 style="font-size: 6.5rem" id="grandtotal_text">0</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Detail Penjualan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm12">
                                    <x-input-with-icon label="Produk" name="nama_produk" icon="ti ti-barcode" height="80px" readonly="true" />
                                    <input type="hidden" id="kode_harga" name="kode_harga">
                                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                                    <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Dus" name="jml_dus" icon="ti ti-box" align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Dus" name="harga_dus" icon="ti ti-moneybag" align="right"
                                                money="true" />
                                            <input type="hidden" id="harga_dus_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Pack" name="jml_pack" icon="ti ti-box" align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Pack" name="harga_pack" icon="ti ti-moneybag" align="right"
                                                money="true" />
                                            <input type="hidden" id="harga_pack_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Pack" name="jml_pcs" icon="ti ti-box" align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Pcs" name="harga_pcs" icon="ti ti-moneybag" align="right"
                                                money="true" />
                                            <input type="hidden" id="harga_pcs_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input status_promosi" name="status_promosi" type="checkbox" value="1"
                                            id="status_promosi">
                                        <label class="form-check-label" for="status_promosi"> Promosi </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="#" id="tambahproduk" class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i>Tambah
                                        Produk</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tabelproduk">
                                            <thead class="text-center table-dark">
                                                <tr>
                                                    <th rowspan="2">Kode</th>
                                                    <th rowspan="2">Nama Barang</th>
                                                    <th colspan="6">Quantity</th>
                                                    <th rowspan="2">Subtotal</th>
                                                    <th rowspan="2">Aksi</th>
                                                </tr>
                                                <tr>
                                                    <th>Dus</th>
                                                    <th>Harga</th>
                                                    <th>Pack</th>
                                                    <th>Harga</th>
                                                    <th>Pcs</th>
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loadproduk">
                                                @php
                                                    $subtotal = 0;
                                                @endphp
                                                @foreach ($detail as $d)
                                                    @php
                                                        $index = $d->kode_harga . $d->status_promosi;
                                                        $jml = convertToduspackpcsv3($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                                        $jml_dus = $jml[0];
                                                        $jml_pack = $jml[1];
                                                        $jml_pcs = $jml[2];

                                                        $subtotal += $d->subtotal;

                                                        if ($d->status_promosi == '1') {
                                                            $color_row = 'bg-warning';
                                                        } else {
                                                            $color_row = '';
                                                        }
                                                    @endphp
                                                    <tr id="index_{{ $index }}" class="{{ $color_row }}">
                                                        <td>
                                                            <input type="hidden" name="kode_harga_produk[]" value="{{ $d->kode_harga }}"
                                                                class="kode_harga" />
                                                            <input type="hidden" name="status_promosi_produk[]" class="status_promosi"
                                                                value="{{ $d->status_promosi }}" />
                                                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon"
                                                                value="{{ $d->kode_kategori_diskon }}" />
                                                            <input type="hidden" name="jumlah_produk[]" value="{{ $d->jumlah }}" />
                                                            <input type="hidden" name="isi_pcs_dus_produk[]" value="{{ $d->isi_pcs_dus }}" />
                                                            <input type="hidden" name="isi_pcs_pack_produk[]" value="{{ $d->isi_pcs_pack }}" />
                                                            {{ $d->kode_harga }}
                                                        </td>
                                                        <td>{{ $d->nama_produk }}</td>
                                                        <td class="text-center">{{ $jml_dus }}</td>
                                                        <td class="text-end">
                                                            {{ formatAngka($d->harga_dus) }}
                                                            <input type="hidden" name="harga_dus_produk[]"
                                                                value="{{ formatAngka($d->harga_dus) }}" />
                                                        </td>
                                                        <td class="text-center">{{ $jml_pack }}</td>
                                                        <td class="text-end">
                                                            {{ formatAngka($d->harga_pack) }}
                                                            <input type="hidden" name="harga_pack_produk[]"
                                                                value="{{ formatAngka($d->harga_pack) }}" />
                                                        </td>
                                                        <td class="text-center">{{ $jml_pcs }}</td>
                                                        <td class="text-end">
                                                            {{ formatAngka($d->harga_pcs) }}
                                                            <input type="hidden" name="harga_pcs_produk[]"
                                                                value="{{ formatAngka($d->harga_pcs) }}" />
                                                        </td>
                                                        <td class="text-end">
                                                            {{ formatAngka($d->subtotal) }}
                                                            <input type="hidden" name="subtotal[]" class="subtotal"
                                                                value="{{ $d->subtotal }}" />
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <a href="#" key="{{ $index }}" class="edit me-2"><i
                                                                            class="ti ti-edit text-success"></i></a>
                                                                </div>
                                                                <div>
                                                                    <a href="#" key="{{ $index }}" class="delete"><i
                                                                            class="ti ti-trash text-danger"></i></a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-dark">
                                                <tr>
                                                    <td colspan="8">SUBTOTAL</td>
                                                    <td class="text-end" id="subtotal">{{ formatAngka($subtotal) }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="divider text-start divider-primary">
                                                <div class="divider-text" style="font-size: 1rem">
                                                    <i class="ti ti-discount"></i> Potongan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">

                                            <x-input-with-group label="AIDA" placeholder="Potongan AIDA" name="potongan_aida" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->potongan_aida) }}" readonly="true" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan SWAN" name="potongan_swan" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->potongan_swan) }}" readonly="true" />
                                            <x-input-with-group label="STICK" placeholder="Potongan STICK" name="potongan_stick" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->potongan_stick) }}" readonly="true" />
                                            <x-input-with-group label="SAMBAL" placeholder="Potongan SAMBAL" name="potongan_sambal" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->potongan_sambal) }}" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="divider text-start divider-primary">
                                                <div class="divider-text" style="font-size: 1rem">
                                                    <i class="ti ti-discount"></i> Potongan Istimewa
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-group label="AIDA" placeholder="Potongan Istimewa AIDA" name="potis_aida"
                                                align="right" money="true" value="{{ formatRupiah($penjualan->potis_aida) }}" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan Istimewa SWAN" name="potis_swan"
                                                align="right" money="true" value="{{ formatRupiah($penjualan->potis_swan) }}" />
                                            <x-input-with-group label="STICK" placeholder="Potongan Istimewa STICK" name="potis_stick"
                                                align="right" money="true" value="{{ formatRupiah($penjualan->potis_stick) }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="divider text-start divider-primary">
                                                <div class="divider-text" style="font-size: 1rem">
                                                    <i class="ti ti-tag"></i> Penyesuaian
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-group label="AIDA" placeholder="Penyesuaian AIDA" name="peny_aida" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->peny_aida) }}" />
                                            <x-input-with-group label="SWAN" placeholder="Penyesuaian SWAN" name="peny_swan" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->peny_swan) }}" />
                                            <x-input-with-group label="STICK" placeholder="Penyesuaian STICK" name="peny_stick" align="right"
                                                money="true" value="{{ formatRupiah($penjualan->peny_stick) }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="divider text-start divider-primary">
                                                <div class="divider-text" style="font-size: 1rem">
                                                    <i class="ti ti-moneybag"></i> Pembayaran
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select" disabled>
                                                    <option value="">Jenis Transaksi</option>
                                                    <option value="T" {{ $penjualan->jenis_transaksi == 'T' ? 'selected' : '' }}>
                                                        TUNAI
                                                    </option>
                                                    <option value="K" {{ $penjualan->jenis_transaksi == 'K' ? 'selected' : '' }}>
                                                        KREDIT</option>
                                                </select>
                                            </div>
                                            <x-input-with-icon label="Grand Total" name="grandtotal" id="grandtotal" icon="ti ti-shopping-cart"
                                                align="right" disabled="true" />
                                        </div>
                                    </div>
                                    <div class="row" id="jenis_bayar_tunai" disabled>
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="jenis_bayar" id="jenis_bayar" class="form-select">
                                                    <option value="">Jenis Bayar</option>
                                                    <option value="TN" {{ $penjualan->jenis_bayar == 'TN' ? 'selected' : '' }}>CASH
                                                    </option>
                                                    <option value="TR" {{ $penjualan->jenis_bayar == 'TR' ? 'selected' : '' }}>
                                                        TRANSFER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="titipan">
                                        <div class="col">
                                            <x-input-with-icon icon="ti ti-moneybag" name="titipan" money="true" align="right" label="Titipan"
                                                value="{{ formatRupiah($titipan) }}" />
                                        </div>
                                    </div>
                                    <div class="row" id="voucher_tunai">
                                        <div class="col">
                                            <x-input-with-icon icon="ti ti-tag" name="voucher" money="true" align="right" label="Voucher"
                                                value="{{ formatRupiah($voucher) }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script type="text/javascript">
    $(document).ready(function() {

        const kode_pelanggan = "{{ Crypt::encrypt($penjualan->kode_pelanggan) }}";

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }



        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: '{{ url()->current() }}',
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });

        // $("#nama_pelanggan").on('click focus', function(e) {
        //     e.preventDefault();
        //     $("#modalPelanggan").modal("show");
        // });

        //Cek file Foto Pelanggan
        function checkFileExistence(fileFoto) {
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            console.log(foto);
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            console.log('File exists');
                            $("#foto").attr("src", foto);
                        } else {
                            console.log('File does not exist');
                            $("#foto").attr("src", fotoDefault);
                        }
                    } else {
                        console.error('Error checking file existence:', xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        //GetPiutang

        function getPiutang(kode_pelanggan) {
            const total_netto = "{{ $total_netto }}";
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    const sisa_piutang = parseInt(response.data) - parseInt(total_netto);
                    $("#sisa_piutang_text").text(convertToRupiah(sisa_piutang));
                    $("#sisa_piutang").val(sisa_piutang);
                    buttonEnable();
                }
            });
        }


        function getFakturkredit(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getFakturkredit`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    const unpaid_faktur = response.data.unpaid_faktur - 1;
                    const max_faktur = response.data.jml_faktur;
                    const siklus_pembayaran = response.data.siklus_pembayaran;
                    // if (unpaid_faktur >= max_faktur && siklus_pembayaran === '0') {
                    //     SwalWarning("nama_pelanggan", "Melebihi Maksimal Faktur Kredit");
                    //     $("#no_faktur").val("");
                    //     $("#tanggal").val("");
                    //     $("#nama_pelanggan").val("");
                    //     $("#kode_pelanggan").val("");
                    //     $("#kode_salesman").val("");
                    //     $("#nama_salesman").val("");
                    //     $('#latitude').text("");
                    //     $('#longitude').text("");
                    //     $('#no_hp_pelanggan').text("");
                    //     $('#limit_pelanggan_text').text("");
                    //     $('#limit_pelanggan').val("");
                    //     $('#alamat_pelanggan').text("");
                    //     $('#sisa_piutang_text').text("");
                    //     $("#jmlfaktur_kredit").text("");
                    //     let fileFoto = "notfound.jpg";
                    //     checkFileExistence(fileFoto);
                    //     //Data Salesman
                    // } else {
                    //     $("#jmlfaktur_kredit").text(response.data.unpaid_faktur - 1);
                    //     $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    //     $("#max_kredit").val(response.data.jml_faktur);
                    // }

                    $("#jmlfaktur_kredit").text(response.data.unpaid_faktur - 1);
                    $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    $("#max_kredit").val(response.data.jml_faktur);

                    buttonEnable();
                }
            });
        }


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`<i class="ti ti-send me-1"></i>Submit`);
        }
        //Get Pelanggan
        function getPelanggan(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    // if (status_aktif_pelanggan === '0') {
                    //     Swal.fire({
                    //         title: "Oops!",
                    //         text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                    //         icon: "warning",
                    //         showConfirmButton: true,
                    //     });
                    // } else {
                    //     $('#kode_pelanggan').val(response.data.kode_pelanggan);
                    //     $('#nama_pelanggan').val(response.data.nama_pelanggan);
                    //     $('#latitude').text(response.data.latitude);
                    //     $('#longitude').text(response.data.longitude);
                    //     $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan);
                    //     $('#limit_pelanggan_text').text(convertToRupiah(response.data
                    //         .limit_pelanggan));
                    //     $('#limit_pelanggan').val(response.data.limit_pelanggan);
                    //     $('#alamat_pelanggan').text(response.data.alamat_pelanggan);
                    //     let fileFoto = response.data.foto;
                    //     checkFileExistence(fileFoto);
                    //     //Data Salesman
                    //     $('#kode_salesman').val(response.data.kode_salesman);
                    //     $('#nama_salesman').val(response.data.nama_salesman);

                    //     //Get Piutang
                    //     getPiutang(kode_pelanggan);
                    //     //Get FaktuR Kredit
                    //     getFakturkredit(kode_pelanggan);

                    //     //open modal
                    //     $('#modalPelanggan').modal('hide');
                    //     buttonEnable();
                    // }
                    $('#kode_pelanggan').val(response.data.kode_pelanggan);
                    $('#nama_pelanggan').val(response.data.nama_pelanggan);
                    $('#latitude').text(response.data.latitude);
                    $('#longitude').text(response.data.longitude);
                    $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan);
                    $('#limit_pelanggan_text').text(convertToRupiah(response.data
                        .limit_pelanggan));
                    $('#limit_pelanggan').val(response.data.limit_pelanggan);
                    $('#alamat_pelanggan').text(response.data.alamat_pelanggan);
                    let fileFoto = response.data.foto;
                    checkFileExistence(fileFoto);
                    //Data Salesman
                    $('#kode_salesman').val(response.data.kode_salesman);
                    $('#nama_salesman').val(response.data.nama_salesman);

                    //Get Piutang
                    getPiutang(kode_pelanggan);
                    //Get FaktuR Kredit
                    getFakturkredit(kode_pelanggan);

                    //open modal
                    $('#modalPelanggan').modal('hide');
                    buttonEnable();

                }
            });
        }

        getPelanggan(kode_pelanggan);


        //GetProduk
        function getHarga(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargabypelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    buttonEnable();
                    $("#loadmodal").html(response);
                }
            });
        }
        //Pilih Produk
        $("#nama_produk").on('click', function(e) {
            e.preventDefault();
            let kode_pelanggan = $("#kode_pelanggan").val();
            if (kode_pelanggan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Pelanggan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_pelanggan").focus();
                    },
                });
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Data Produk');
                getHarga(kode_pelanggan);
            }
        });

        $(document).on('click', '.pilihProduk', function(e) {
            e.preventDefault();
            let kode_harga = $(this).attr('kode_harga');
            let nama_pelanggan = $("#nama_pelanggan").val();
            let nama_produk = $(this).attr('nama_produk');
            let harga_dus = $(this).attr('harga_dus');
            let harga_pack = $(this).attr('harga_pack');
            let harga_pcs = $(this).attr('harga_pcs');

            let harga_dus_produk = $(this).attr('harga_dus');
            let harga_pack_produk = $(this).attr('harga_pack');
            let harga_pcs_produk = $(this).attr('harga_pcs');

            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');

            let kode_kategori_diskon = $(this).attr('kode_kategori_diskon');
            if ($('#status_promosi').is(":checked")) {
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            }
            $("#kode_harga").val(kode_harga);
            $("#nama_produk").val(nama_produk);
            $("#harga_dus").val(harga_dus);
            $("#harga_pack").val(harga_pack);
            $("#harga_pcs").val(harga_pcs);

            $("#harga_dus_produk").val(harga_dus_produk);
            $("#harga_pack_produk").val(harga_pack_produk);
            $("#harga_pcs_produk").val(harga_pcs_produk);


            $("#isi_pcs_dus").val(isi_pcs_dus);
            $("#isi_pcs_pack").val(isi_pcs_pack);

            $("#kode_kategori_diskon").val(kode_kategori_diskon);


            //Disabled Harga
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#jml_pack").prop('disabled', false);
            }
            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                $("#harga_dus").prop('disabled', false);
                if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                    $("#harga_pack").prop('disabled', true);
                } else {
                    $("#harga_pack").prop('disabled', false);
                }
                $("#harga_pcs").prop('disabled', false);
            } else {
                $("#harga_dus").prop('disabled', true);
                $("#harga_pack").prop('disabled', true);
                $("#harga_pcs").prop('disabled', true);
            }

            $("#modal").modal("hide");
        });


        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            let jml_dus = Math.floor(jumlah / isi_pcs_dus);
            let sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '') {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                jml_pack = 0;
                sisa_pack = sisa_dus;
            }
            let jml_pcs = sisa_pack;


            let data = {
                "dus": jml_dus,
                "pack": jml_pack,
                "pcs": jml_pcs
            };

            return data;
        }


        function addProduk() {
            var kode_harga = $("#kode_harga").val();
            var nama_produk = $("#nama_produk").val();
            var jml_dus = $("#jml_dus").val();
            var jml_pack = $("#jml_pack").val();
            var jml_pcs = $("#jml_pcs").val();
            var harga_dus = $("#harga_dus").val();
            var harga_pack = $("#harga_pack").val();
            var harga_pcs = $("#harga_pcs").val();
            var isi_pcs_dus = $("#isi_pcs_dus").val();
            var isi_pcs_pack = $("#isi_pcs_pack").val();
            var kode_kategori_diskon = $("#kode_kategori_diskon").val();


            if ($('#status_promosi').is(":checked")) {
                var status_promosi = $("#status_promosi").val();
            } else {
                var status_promosi = 0;
            }

            var jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            var jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            var jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            var hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            var hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            var hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            var jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) + jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;

            let index = kode_harga + status_promosi;

            let bgcolor = "";
            if (status_promosi == '1') {
                bgcolor = "bg-warning text-white";
                var hargadus = 0;
                var hargapack = 0;
                var hargapcs = 0;
                var harga_dus = 0;
                var harga_pack = 0;
                var harga_pcs = 0;
            } else {
                bgcolor = bgcolor;
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));


            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },
                });
            } else if ($('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_produk").focus();
                    },

                });
            } else {
                let produk = `
                    <tr id="index_${index}" class="${bgcolor}">
                        <td>
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="text-end">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="text-center">${pack===0 ? '' :pack}</td>
                        <td class="text-end">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="text-end">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="text-end">
                            ${convertToRupiah(subtotal)}
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        </td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div>
                                 <a href="#" key="${index}" class="edit me-2"><i class="ti ti-edit text-success"></i></a>
                              </div>
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>

                        </td>
                    </tr>
                `;

                //append to table
                $('#loadproduk').append(produk);
                $("#kode_harga").val("");
                $("#nama_produk").val("");
                $("#jml_dus").val("");
                $("#jml_pack").val("");
                $("#jml_pcs").val("");
                $("#harga_dus").val("");
                $("#harga_pack").val("");
                $("#harga_pcs").val("");

                $("#harga_dus_produk").val("");
                $("#harga_pack_produk").val("");
                $("#harga_pcs_produk").val("");
                $("#status_promosi").prop('checked', false);

                loadsubtotal();


            }

        }

        $("#status_promosi").change(function() {
            let harga_dus = $("#harga_dus_produk").val();
            let harga_pack = $("#harga_pack_produk").val();
            let harga_pcs = $("#harga_pcs_produk").val();
            if (this.checked) {
                $("#harga_dus").val(0);
                $("#harga_pack").val(0);
                $("#harga_pcs").val(0);
            } else {
                $("#harga_dus").val(harga_dus);
                $("#harga_pack").val(harga_pack);
                $("#harga_pcs").val(harga_pcs);
            }
        });
        //Tambah Item Produk
        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            addProduk();
        });


        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let key = $(this).attr("key");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(`#index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });



        let currentRow;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_harga = currentRow.find('.kode_harga').val();
            let nama_produk = currentRow.find('td:eq(1)').text();
            let jml_dus = currentRow.find('td:eq(2)').text();
            let harga_dus = currentRow.find('td:eq(3)').text();
            let jml_pack = currentRow.find('td:eq(4)').text();
            let harga_pack = currentRow.find('td:eq(5)').text();
            let jml_pcs = currentRow.find('td:eq(6)').text();
            let harga_pcs = currentRow.find('td:eq(7)').text();
            let subtotal = currentRow.find('td:eq(8)').text();
            let kode_pelanggan = $("#kode_pelanggan").val();
            let status_promosi = currentRow.find('.status_promosi').val();
            let index_old = kode_harga + "" + status_promosi;
            console.log(kode_harga);
            console.log(status_promosi);
            console.log(index_old);
            //alert(status_promosi);
            let dataProduk = {
                'kode_pelanggan': kode_pelanggan,
                'kode_harga': kode_harga,
                'nama_produk': nama_produk,
                'jml_dus': jml_dus,
                'harga_dus': harga_dus,
                'jml_pack': jml_pack,
                'harga_pack': harga_pack,
                'jml_pcs': jml_pcs,
                'harga_pcs': harga_pcs,
                'status_promosi': status_promosi,
                'index_old': index_old
            };
            $.ajax({
                type: 'POST',
                url: '/penjualan/editproduk',
                data: {
                    _token: "{{ csrf_token() }}",
                    dataproduk: dataProduk
                },
                cache: false,
                success: function(respond) {
                    $("#modaleditProduk").modal("show");
                    $("#modaleditProduk").find(".modal-title").text("Edit Produk");
                    $("#loadmodaleditProduk").html(respond);
                }
            });
        });

        $(document).on('submit', '#formEditproduk', function(event) {
            event.preventDefault();
            let kode_harga = $(this).find("#kode_harga").val();
            let nama_produk = $(this).find("#kode_harga").find(':selected').text();
            let jml_dus = $(this).find("#jml_dus").val();
            let jml_pack = $(this).find("#jml_pack").val();
            let jml_pcs = $(this).find("#jml_pcs").val();
            let harga_dus = $(this).find("#harga_dus").val();
            let harga_pack = $(this).find("#harga_pack").val();
            let harga_pcs = $(this).find("#harga_pcs").val();
            let isi_pcs_dus = $(this).find("#isi_pcs_dus").val();
            let isi_pcs_pack = $(this).find("#isi_pcs_pack").val();
            let kode_kategori_diskon = $(this).find("#kode_kategori_diskon").val();
            let index_old = $(this).find("#index_old").val();
            let status_promosi;
            // if ($(this).find('#status_promosi_edit').is(":checked")) {
            //     let status_promosi =
            // } else {
            //     let status_promosi = 0;
            // }
            if ($(this).find('#status_promosi_edit').is(':checked')) {
                status_promosi = 1;
            } else {
                status_promosi = 0;
            }




            let jmldus = jml_dus != "" ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            let jmlpack = jml_pack != "" ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            let jmlpcs = jml_pcs != "" ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            let hargadus = harga_dus != "" ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            let hargapack = harga_pack != "" ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            let hargapcs = harga_pcs != "" ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            let jumlah = (jmldus * parseInt(isi_pcs_dus)) + (jmlpack * (parseInt(isi_pcs_pack))) +
                jmlpcs;

            let data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            let dus = data.dus;
            let pack = data.pack;
            let pcs = data.pcs;


            let index = kode_harga + status_promosi;
            console.log(index_old);
            let bgcolor = "";
            if (status_promosi == '1') {
                bgcolor = "bg-warning text-white";
                hargadus = 0;
                hargapack = 0;
                hargapcs = 0;
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                bgcolor = bgcolor;
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(
                hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));

            let newRow = `
                    <tr id="index_${index}" class="${bgcolor}">
                        <td>
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="status_promosi_produk[]" value="${status_promosi}" class="status_promosi"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                            ${kode_harga}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">
                           ${dus===0 ? '' : dus}
                        </td>
                        <td class="text-end">
                           ${harga_dus}
                           <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        </td>
                        <td class="text-center">${pack===0 ? '' :pack}</td>
                        <td class="text-end">
                           ${harga_pack}
                           <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        </td>
                        <td class="text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="text-end">
                           ${harga_pcs}
                           <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                            ${convertToRupiah(subtotal)}
                        </td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div>
                                 <a href="#" key="${index}" class="edit me-2"><i class="ti ti-edit text-success"></i></a>
                              </div>
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>

                        </td>
                    </tr>
                `;
            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jml_dus").focus();
                    },
                });
            } else if (index != index_old && $('#tabelproduk').find('#index_' + index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_harga").focus();
                    },
                });
            } else {
                currentRow.replaceWith(newRow);

                $("#modaleditProduk").modal("hide");
            }
            loadsubtotal();
        });


        function loadsubtotal() {
            let subtotal = 0;
            let valSubtotal = $("#tabelproduk").find(".subtotal");

            valSubtotal.each(function() {
                let val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });

            $("#subtotal").text(convertToRupiah(subtotal));
            hitungdiskonAida();
            hitungdiskonSwan();
            hitungdiskonStick();
            hitungdiskonSC();
            hitungdiskonSP();
            calculateGrandtotal();
        }


        //   loadsubtotal();
        // Function to calculate total quantity based on category
        function calculateTotalQuantityByCategory(category) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_kategori_diskon').val() === category) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('td:eq(2)').text());
                    }
                }
            });

            return totalQuantity;
        }

        function calculateDiscount(totalQuantity, category) {
            let discount = 0;
            let discount_tunai = 0;
            let total_discount = 0;
            let nama_pelanggan = $("#nama_pelanggan").val();
            let jenis_transaksi = $("#jenis_transaksi").val();
            // Define discount rules based on quantity range and category
            const discountRules = <?php echo $diskon; ?>;

            // Find the applicable discount rule based on total quantity and category
            for (let i = 0; i < discountRules.length; i++) {
                if (totalQuantity >= discountRules[i].min_qty &&
                    totalQuantity <= discountRules[i].max_qty &&
                    category === discountRules[i].kode_kategori_diskon) {
                    if (jenis_transaksi === 'T') {
                        discount = totalQuantity * discountRules[i].diskon;
                        discount_tunai = totalQuantity * discountRules[i].diskon_tunai;
                        total_discount = discount + discount_tunai;
                    } else {
                        total_discount = totalQuantity * discountRules[i].diskon;
                    }

                    if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                        total_discount = 0;
                    }
                    break;
                }
            }

            return total_discount;
        }

        $("#jenis_transaksi").change(function() {
            loadsubtotal();
            showhidetunai();
            showhidekredit();
        });

        function hitungdiskonAida() {
            let totalQuantity = calculateTotalQuantityByCategory('D002');
            let diskon = calculateDiscount(totalQuantity, 'D002');
            $("#potongan_aida").val(convertToRupiah(diskon));
            return diskon;
        }


        function hitungdiskonSwan() {
            let totalQuantity = calculateTotalQuantityByCategory('D001');
            let diskon = calculateDiscount(totalQuantity, 'D001');
            $("#potongan_swan").val(convertToRupiah(diskon));
            return diskon;
        }

        function hitungdiskonStick() {
            let totalQuantity = calculateTotalQuantityByCategory('D003');
            let diskon = calculateDiscount(totalQuantity, 'D003');
            $("#potongan_stick").val(convertToRupiah(diskon));
        }

        function hitungdiskonSP() {
            let totalQuantity = calculateTotalQuantityByCategory('D004');
            let diskon = calculateDiscount(totalQuantity, 'D004');
            $("#potongan_sp").val(convertToRupiah(diskon));
        }


        function hitungdiskonSC() {
            let totalQuantity = calculateTotalQuantityByCategory('D005');
            let diskon = calculateDiscount(totalQuantity, 'D005');
            $("#potongan_sambal").val(convertToRupiah(diskon));
        }


        function calculateGrandtotal() {
            const subtotalVal = $("#subtotal").text();
            const subtotal = subtotalVal != "" ? parseInt(subtotalVal.replace(/\./g, '')) : 0;
            const potonganSwanVal = $("#potongan_swan").val();
            const potongan_swan = potonganSwanVal != "" ? parseInt(potonganSwanVal.replace(/\./g, '')) : 0;

            const potonganAidaVal = $("#potongan_aida").val();
            const potongan_aida = potonganAidaVal != "" ? parseInt(potonganAidaVal.replace(/\./g, '')) : 0;

            const potonganStickVal = $("#potongan_stick").val();
            const potongan_stick = potonganStickVal != "" ? parseInt(potonganStickVal.replace(/\./g, '')) : 0;

            const potonganSambalVal = $("#potongan_sambal").val();
            const potongan_sambal = potonganSambalVal != "" ? parseInt(potonganSambalVal.replace(/\./g, '')) :
                0;

            const total_potongan = parseInt(potongan_swan) + parseInt(potongan_aida) + parseInt(
                potongan_stick) + parseInt(potongan_sambal);

            //Potongan Istimewa
            const potisAidaVal = $("#potis_aida").val();
            const potis_aida = potisAidaVal != "" ? parseInt(potisAidaVal.replace(/\./g, '')) : 0;

            const potisSwanVal = $("#potis_swan").val();
            const potis_swan = potisSwanVal != "" ? parseInt(potisSwanVal.replace(/\./g, '')) : 0;

            const potisStickVal = $("#potis_stick").val();
            const potis_stick = potisStickVal != "" ? parseInt(potisStickVal.replace(/\./g, '')) : 0;

            const total_potongan_istimewa = parseInt(potis_aida) + parseInt(potis_swan) + parseInt(potis_stick);

            //Penyesuaian
            const penyAidaVal = $("#peny_aida").val();
            const peny_aida = penyAidaVal != "" ? parseInt(penyAidaVal.replace(/\./g, '')) : 0;

            const penySwanVal = $("#peny_swan").val();
            const peny_swan = penySwanVal != "" ? parseInt(penySwanVal.replace(/\./g, '')) : 0;

            const penyStickVal = $("#peny_stick").val();
            const peny_stick = penyStickVal != "" ? parseInt(penyStickVal.replace(/\./g, '')) : 0;

            const total_penyesuaian = parseInt(peny_aida) + parseInt(peny_swan) + parseInt(peny_stick);



            const grandtotal = parseInt(subtotal) - parseInt(total_potongan) - parseInt(
                total_potongan_istimewa) - parseInt(total_penyesuaian);
            $("#grandtotal_text").text(convertToRupiah(grandtotal));
            $("#grandtotal").val(convertToRupiah(grandtotal));
            console.log(grandtotal);
        }

        calculateGrandtotal();

        $("#potongan_aida, #potongan_swan, #potongan_stick, #potongan_sambal, #potis_aida, #potis_swan, #potis_stick, #peny_aida, #peny_swan, #peny_stick ")
            .on('keyup keydown', function() {
                calculateGrandtotal();
            });

        function showhidetunai() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'T') {
                $("#jenis_bayar_tunai").show();
                $("#voucher_tunai").show();
            } else {
                $("#jenis_bayar_tunai").hide();
                $("#voucher_tunai").hide();
            }
        }

        function showhidekredit() {
            const jenis_transaksi = $("#jenis_transaksi").val();
            if (jenis_transaksi == 'K') {
                $("#titipan").show();
            } else {
                $("#titipan").hide();
            }
        }

        showhidetunai();
        showhidekredit();



        $("#formPenjualan").submit(function(e) {
            const no_faktur = $("#no_faktur").val();
            const tanggal = $("#tanggal").val();
            const kode_pelanggan = $("#kode_pelanggan").val();
            const kode_salesman = $("#kode_salesman").val();
            const sisa_piutang = $("#sisa_piutang").val();
            const gt = $("#grandtotal").val();
            const grandtotal = gt != "" ? parseInt(gt.replace(/\./g, '')) : 0;
            const totalPiutang = parseInt(sisa_piutang) + parseInt(grandtotal);
            const limit_pelanggan = $("#limit_pelanggan").val();
            const siklus_pembayaran = $("#siklus_pembayaran").val();
            const max_kredit = $("#max_kredit").val();
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
            const keterangan = $("#keterangan").val();
            if (no_faktur == '') {
                SwalWarning('no_faktur', 'No. Faktur Tidak Boleh Kosong');
                return false;
            } else if (tanggal == '') {
                SwalWarning('tanggal', 'Tanggal Tidak Boleh Kosong');
                return false;
            } else if (kode_pelanggan == "") {
                SwalWarning('nama_pelanggan', 'Pelanggan Tidak Boleh Kosong');
                return false;
            } else if (kode_salesman == "") {
                SwalWarning('nama_salesman', 'Salesman Tidak Boleh Kosong');
                return false;
            } else if ($('#loadproduk tr').length == 0) {
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "") {
                SwalWarning('jenis_transaksi', 'Jenis Transaksi Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "T" && jenis_bayar == "") {
                SwalWarning('jenis_bayar', 'Jenis Bayar Tidak Boleh Kosong');
                return false;
            } else if (jenis_transaksi == "K" && sisa_piutang > 0 && keterangan == "") {
                SwalWarning('keterangan', 'Keterangan Harus Diisi !');
                return false;
            } else {
                buttonDisable();
            }
            // else if (jenis_transaksi == "K" && siklus_pembayaran === '0' && parseInt(totalPiutang) >
            //     parseInt(limit_pelanggan)) {
            //     SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
            //     return false;
            // } else if (jenis_transaksi == "K" && siklus_pembayaran === '1' && parseInt(grandtotal) >
            //     parseInt(limit_pelanggan)) {
            //     SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
            //     return false;

            // }
        });

    });
</script>
@endpush
