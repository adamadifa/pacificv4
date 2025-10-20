@extends('layouts.app')
@section('titlepage', 'Input Penjualan')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Penjualan</span> / <span>Input Penjualan</span>
@endsection
<form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
    @csrf
    <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
    <input type="hidden" name="max_kredit" id="max_kredit">
    @if (!empty($piutang))
        <div class="row">
            <div class="col">
                <div class="alert alert-danger">
                    <h5><i class="ti ti-info-circle"></i> Piutang</h5>
                    <p>Pelanggan ini memiliki Faktur Belum Lunas sebanyak {{ $piutang->count() }} Faktur</p>
                    <table class="table">
                        <tr>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Total Piutang</th>
                        </tr>
                        @foreach ($piutang as $d)
                            @php
                                $saldo_piutang = $d->total_piutang - $d->jmlbayar;
                            @endphp
                            <tr>
                                <td>{{ $d->no_faktur }}</td>
                                <td>{{ $d->tanggal }}</td>
                                <td class="text-end">{{ formatAngka($saldo_piutang) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Faktur" name="no_faktur" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar"
                                datepicker="flatpickr-date" value="{{ date('Y-m-d') }}" />
                            <x-input-with-icon label="Pelanggan" name="nama_pelanggan" icon="ti ti-user"
                                readonly="true" />
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan"
                                value="{{ $kode_pelanggan }}">
                            <input type="hidden" id="kode_wilayah" name="kode_wilayah">
                            <x-input-with-icon label="Salesman" name="nama_salesman" icon="ti ti-user"
                                readonly="true" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman">
                            <div class="form-group mb-3">
                                <textarea name="keterangan" class="form-control" id="" cols="30" rows="5" id="keterangan"
                                    placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="../../assets/img/elements/2.jpg" alt="Card image cap"
                            style="height:250px; object-fit:cover" id="foto">
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
                                <div class="icon-cart mt-1">
                                    <i class="ti ti-shopping-bag text-primary" style="font-size: 4rem"></i>
                                </div>
                                <div class="mt-2">
                                    <h1 style="font-size: 2.5rem" id="grandtotal_text">0</h1>
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
                            <div class="row" style="display: none">
                                <div class="col-lg-4 col-md-12 col-sm12">
                                    <x-input-with-icon label="Produk" name="nama_produk" icon="ti ti-barcode"
                                        height="80px" readonly="true" />
                                    <input type="hidden" id="kode_harga" name="kode_harga">
                                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                                    <input type="hidden" id="kode_kategori_diskon" name="kode_kategori_diskon">
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Dus" name="jml_dus" icon="ti ti-box"
                                                align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Dus" name="harga_dus"
                                                icon="ti ti-moneybag" align="right" money="true" />
                                            <input type="hidden" id="harga_dus_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Pack" name="jml_pack" icon="ti ti-box"
                                                align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Pack" name="harga_pack"
                                                icon="ti ti-moneybag" align="right" money="true" />
                                            <input type="hidden" id="harga_pack_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Pack" name="jml_pcs" icon="ti ti-box"
                                                align="right" money="true" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-input-with-icon label="Harga / Pcs" name="harga_pcs"
                                                icon="ti ti-moneybag" align="right" money="true" />
                                            <input type="hidden" id="harga_pcs_produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input status_promosi" name="status_promosi"
                                            type="checkbox" value="1" id="status_promosi">
                                        <label class="form-check-label" for="status_promosi"> Promosi </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="#" id="tambahproduk" class="btn btn-primary w-100"><i
                                            class="ti ti-plus me-1"></i>Tambah
                                        Produk</a>
                                </div>
                            </div>
                            {{-- <div class="row" >
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
                                            <tbody id="loadproduk"></tbody>
                                            <tfoot class="table-dark">
                                                <tr>
                                                    <td colspan="8">SUBTOTAL</td>
                                                    <td class="text-end" id="subtotal"></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col">
                                    <table class="table" id="tabelproduk">
                                        <tbody id="loadproduk"></tbody>
                                        <tfoot>
                                            <tr style="background-color: #e9e9e9be">
                                                <td>SUBTOTAL</td>
                                                <td class="text-end fw-bold" id="subtotal"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                                            <x-input-with-group label="AIDA" placeholder="Potongan AIDA"
                                                name="potongan_aida" align="right" money="true"
                                                readonly="true" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan SWAN"
                                                name="potongan_swan" align="right" money="true"
                                                readonly="true" />
                                            <x-input-with-group label="STICK" placeholder="Potongan STICK"
                                                name="potongan_stick" align="right" money="true"
                                                readonly="true" />
                                            <x-input-with-group label="SAMBAL" placeholder="Potongan SAMBAL"
                                                name="potongan_sambal" align="right" money="true"
                                                readonly="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12" style="display: none">
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
                                            <x-input-with-group label="AIDA" placeholder="Potongan Istimewa AIDA"
                                                name="potis_aida" align="right" money="true" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan Istimewa SWAN"
                                                name="potis_swan" align="right" money="true" />
                                            <x-input-with-group label="STICK" placeholder="Potongan Istimewa STICK"
                                                name="potis_stick" align="right" money="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12" style="display: none">
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
                                            <x-input-with-group label="AIDA" placeholder="Penyesuaian AIDA"
                                                name="peny_aida" align="right" money="true" />
                                            <x-input-with-group label="SWAN" placeholder="Penyesuaian SWAN"
                                                name="peny_swan" align="right" money="true" />
                                            <x-input-with-group label="STICK" placeholder="Penyesuaian STICK"
                                                name="peny_stick" align="right" money="true" />
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
                                            <table class="table">
                                                <tr>
                                                    <th>Saldo Voucher</th>
                                                    <td id="saldo_voucher_text"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="jenis_transaksi" id="jenis_transaksi"
                                                    class="form-select">
                                                    <option value="">Jenis Transaksi</option>
                                                    <option value="T">TUNAI</option>
                                                    <option value="K">KREDIT</option>
                                                </select>
                                            </div>
                                            <x-input-with-icon label="Grand Total" name="grandtotal" id="grandtotal"
                                                icon="ti ti-shopping-cart" align="right" disabled="true" />
                                        </div>
                                    </div>
                                    <div class="row" id="jenis_bayar_tunai">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="jenis_bayar" id="jenis_bayar" class="form-select">
                                                    <option value="">Jenis Bayar</option>
                                                    <option value="TN">CASH</option>
                                                    <option value="TR">TRANSFER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="titipan">
                                        <div class="col">
                                            <x-input-with-icon icon="ti ti-moneybag" name="titipan" money="true"
                                                align="right" label="Titipan" />
                                        </div>
                                    </div>
                                    <div class="row" id="voucher_tunai">
                                        <div class="col">
                                            <x-input-with-icon icon="ti ti-tag" name="voucher" money="true"
                                                align="right" label="Voucher" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <button class="btn btn-primary w-100" id="btnSimpan"><i
                                                        class="ti ti-send me-1"></i>Submit</button>
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


@endsection
@push('myscript')
<script type="text/javascript">
    $(document).ready(function() {
        let kode_wilayah = 0;
        let jmlfakturbelumlunas = 0;
        let jmlmaxfaktur = 0;
        let saldo_voucher = 0;
        let kode_cabang_pelanggan = '';
        let kode_pel = '';

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
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#sisa_piutang_text").text(convertToRupiah(response.data));
                    $("#sisa_piutang").val(response.data);
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
                    const unpaid_faktur = response.data.unpaid_faktur;
                    const max_faktur = response.data.jml_faktur;
                    const siklus_pembayaran = response.data.siklus_pembayaran;
                    jmlfakturbelumlunas = unpaid_faktur;
                    jmlmaxfaktur = max_faktur;
                    console.log(jmlfakturbelumlunas);
                    console.log(jmlmaxfaktur);
                    // if (unpaid_faktur >= max_faktur && siklus_pembayaran === '0' || unpaid_faktur >= max_faktur &&
                    //     siklus_pembayaran == '0') {
                    //     Swal.fire({
                    //         title: "Oops!",
                    //         text: "Melebih Maksimal Faktur Kredit !, Maksimal Faktur Kredit Adalah : " + max_faktur,
                    //         icon: "warning",
                    //         showConfirmButton: true,
                    //         didClose: (e) => {
                    //             $("#no_faktur").val("");
                    //             $("#tanggal").val("");
                    //             $("#nama_pelanggan").val("");
                    //             $("#kode_pelanggan").val("");
                    //             $("#kode_salesman").val("");
                    //             $("#nama_salesman").val("");
                    //             $('#latitude').text("");
                    //             $('#longitude').text("");
                    //             $('#no_hp_pelanggan').text("");
                    //             $('#limit_pelanggan_text').text("");
                    //             $('#limit_pelanggan').val("");
                    //             $('#alamat_pelanggan').text("");
                    //             $('#sisa_piutang_text').text("");
                    //             $("#jmlfaktur_kredit").text("");
                    //             let fileFoto = "notfound.jpg";
                    //             checkFileExistence(fileFoto);
                    //             window.location.href = "/sfa/pelanggan/{{ Crypt::encrypt($kode_pelanggan) }}/show";
                    //         },
                    //     });

                    //     //Data Salesman
                    // } else {
                    //     $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
                    //     $("#siklus_pembayaran").val(response.data.siklus_pembayaran);
                    //     $("#max_kredit").val(response.data.jml_faktur);
                    // }
                    $("#jmlfaktur_kredit").text(response.data.unpaid_faktur);
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
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        kode_cabang_pelanggan = response.data.kode_cabang;
                        kode_pel = response.data.kode_pelanggan;
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
                        $("#kode_wilayah").val(response.data.kode_wilayah);
                        $("#saldo_voucher_text").text(response.saldo_voucher);
                        saldo_voucher = response.saldo_voucher
                        console.log(kode_wilayah);
                        //Get Piutang
                        getPiutang(kode_pelanggan);
                        //Get FaktuR Kredit
                        getFakturkredit(kode_pelanggan);
                        generatenofaktur();
                        //open modal
                        $('#modalPelanggan').modal('hide');
                        buttonEnable();
                    }

                }
            });
        }

        getPelanggan("{{ Crypt::encrypt($kode_pelanggan) }}");

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
            //addProduk();
            const kode_pelanggan = "{{ Crypt::encrypt($kode_pelanggan) }}"
            $("#modaleditProduk").modal("show");
            $("#modaleditProduk").find(".modal-title").text("Tambah Produk");
            $("#loadmodaleditProduk").load(`/sfa/penjualan/${kode_pelanggan}/addproduk`);
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
                    $(`.index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });



        let currentRow;


        $(document).on('submit', '#formAddproduk', function(event) {
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
            let kode_produk = $(this).find("#kode_produk").val();
            // alert(kode_produk);
            // return false;
            let index_old = $(this).find("#index_old").val();
            let status_promosi;
            // if ($(this).find('#status_promosi_edit').is(":checked")) {
            //     let status_promosi =
            // } else {
            //     let status_promosi = 0;
            // }
            if ($(this).find('#status_promosi').is(':checked')) {
                status_promosi = 1;
            } else {
                status_promosi = 0;
            }

            //alert(status_promosi);


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

            let bgcolor = "";
            if (status_promosi == '1') {
                bgcolor = "bg-warning text-white";
                colorproduk = "";
                hargadus = 0;
                hargapack = 0;
                hargapcs = 0;
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                bgcolor = bgcolor;
                colorproduk = "#e9e9e9";
            }
            let subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(
                hargapack)) + (
                parseInt(pcs) * parseInt(hargapcs));
            let subtotal_dus = parseInt(dus) * parseInt(hargadus);
            let subtotal_pack = parseInt(pack) * parseInt(hargapack);
            let subtotal_pcs = parseInt(pcs) * parseInt(hargapcs);

            let produk = `
                    <tr  class="${bgcolor} index_${index}">
                        <td style="background-color: ${colorproduk};">
                            <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                            <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                            <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                            <input type="hidden" name="kode_produk[]" class="kode_produk" value="${kode_produk}"/>
                            <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                            <input type="hidden" name="jumlah_dus[]" class="jumlah_dus" value="${dus}"/>
                            <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                            <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                             <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                             <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                             <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                             <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                            ${nama_produk}
                        </td>
                        <td style="background-color: ${colorproduk};">
                            <div class="d-flex justify-content-end">
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>
                        </td>

                    </tr>

                    ${dus != '' || dus != '0' ? `
                    <tr class="${bgcolor} index_${index}">
                        <td>
                           ${dus} Dus x  ${harga_dus}
                        </td>
                        <td class="text-end fw-bold">
                            ${convertToRupiah(subtotal_dus)}
                        </td>
                    </tr>
                    ` : ''}

                ${pack != '' || pack != '0' ? `
                <tr class="${bgcolor} index_${index}">
                    <td>
                       ${pack} Pack x  ${harga_pack}
                    </td>
                    <td class="text-end fw-bold">
                        ${convertToRupiah(subtotal_pack)}
                    </td>
                </tr>
                ` : ''}

                ${pcs != '0' || pcs != '' ? `
                <tr class="${bgcolor} index_${index}">
                    <td>
                       ${pcs} Pcs x  ${harga_pcs}
                    </td>
                    <td class="text-end fw-bold">
                        ${convertToRupiah(subtotal_pcs)}
                    </td>
                </tr>
                ` : ''}
                `;

            //append to table

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
            } else if ($('#tabelproduk').find('.index_' + index).length > 0) {
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
                $('#loadproduk').append(produk);
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
            // hitungdiskonProductBP500();

            calculateGrandtotal();
        }



        // Function to calculate total quantity based on category
        function calculateTotalQuantityByCategory(category) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_kategori_diskon').val() === category) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('.jumlah_dus').val());
                    }
                }
            });
            // console.log(category + ': ' + totalQuantity);
            return totalQuantity;
        }


        function calculateTotalQuantityByProduct(kode_produk) {
            let totalQuantity = 0;
            // Loop through each row in the table
            $('#tabelproduk tbody tr').each(function() {
                // Check if the category matches
                if ($(this).find('.kode_produk').val() === kode_produk) {
                    // Add quantity to total if category matches
                    if ($(this).find('.status_promosi').val() === '0') {
                        totalQuantity += parseInt($(this).find('.jumlah_dus').val());
                    }
                }
            });
            // console.log(category + ': ' + totalQuantity);
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


        function hitungdiskonProductBP500() {
            let totalQuantity = calculateTotalQuantityByProduct('BP500');
            let diskon = totalQuantity * 2000;
            return diskon;

        }

        function hitungdiskonSPPP500() {
            let totalQuantity = calculateTotalQuantityByCategory('D008');
            let diskon = calculateDiscount(totalQuantity, 'D008');
            return diskon;

        }

        function hitungdiskonSPPP1000() {
            let blacklist_pelanggan = [
                'BGR-06675',
                'BGR-06827',
                'BGR-06854',
                'BKI-00068',
                'BKI-00122',
                'BKI-00633',
                'BKI-00841',
                'BKI-00869',
                'BKI-00959',
                'BKI-00982',
                'BKI-01041',
                'BKI-01108',
                'BKI-01163',
                'BKI-01164',
                'BKI-01196',
                'BKI-01198',
                'BKI-01199',
                'BKI-01222',
                'BKI-01223',
                'BKI-01234',
                'BKI-01277',
                'BKI-01282',
                'BKI-01283',
                'BKI-01292',
                'BKI-01299',
                'BKI-01383',
                'BKI-01387',
                'BGR-06648',
                'BKI-00052',
                'BKI-00100',
                'BKI-00110',
                'BKI-00113',
                'BKI-00116',
                'BKI-00140',
                'BKI-00268',
                'BKI-00293',
                'BKI-00295',
                'BKI-00315',
                'BKI-00341',
                'BKI-00350',
                'BKI-00351',
                'BKI-00353',
                'BKI-00370',
                'BKI-00381',
                'BKI-00384',
                'BKI-00450',
                'BKI-00552',
                'BKI-00604',
                'BKI-00615',
                'BKI-00671',
                'BKI-00800',
                'BKI-00813',
                'BKI-00821',
                'BKI-00831',
                'BKI-00858',
                'BKI-00871',
                'BKI-00872',
                'BKI-00879',
                'BKI-00884',
                'BKI-00985',
                'BKI-01006',
                'BKI-01026',
                'BKI-01052',
                'BKI-01081',
                'BKI-01111',
                'BKI-01112',
                'BKI-01119',
                'BKI-01151',
                'BKI-01171',
                'BKI-01172',
                'BKI-01202',
                'BKI-01203',
                'BKI-01278',
                'BKI-01287',
                'BKI-01291',
                'BKI-01295',
                'BKI-01296',
                'BKI-01297',
                'BKI-01375',
                'BKI-01376',
                'BKI-01377',
                'BKI-01386',
                'BKI-01388',

                'BKI-01392',
                'BKI-01394',
                'BKI-01400',
                'BGR-06669',
                'BGR-06759',
                'BKI-00007',
                'BKI-00008',
                'BKI-00029',
                'BKI-00121',
                'BKI-00258',
                'BKI-00367',
                'BKI-00518',
                'BKI-00585',
                'BKI-00686',
                'BKI-00735',
                'BKI-00773',
                'BKI-00777',
                'BKI-00902',
                'BKI-00911',
                'BKI-00912',
                'BKI-00917',
                'BKI-00936',
                'BKI-00973',
                'BKI-00979',
                'BKI-01023',
                'BKI-01040',
                'BKI-01049',
                'BKI-01187',
                'BKI-01193',
                'BKI-01194',
                'BKI-01201',
                'BKI-01236',
                'BKI-01238',
                'BKI-01240',
                'BKI-01242',
                'BKI-01261',
                'BKI-01266',
                'BKI-01285',
                'BKI-01294',
                'BGR-07636',
                'BRG-07591',
                'BRG-07480',
                'BRG-07465',
                'BRG-07650',
                'BRG-07982',
                'BRG-07984',
                'BRG-07751',
                'BRG-07638',
                'BRG-07597',
                'BRG-07643',
                'BRG-07966',
                'BRG-07809',
                'BRG-07693',
                'BRG-07784',

            ];
            let totalQuantity = calculateTotalQuantityByCategory('D009');
            let diskon = calculateDiscount(totalQuantity, 'D009');
            if (blacklist_pelanggan.includes(kode_pel)) {
                diskon = 0;
            }
            return diskon;

        }

        function hitungdiskonSAOSME() {
            const kode_cabang_diskon_saosme = ['BTN', 'CRB'];
            let totalQuantity = calculateTotalQuantityByCategory('D010');
            let diskon = calculateDiscount(totalQuantity, 'D010');
            if (kode_cabang_diskon_saosme.includes(kode_cabang_pelanggan)) {
                diskon = diskon;
            } else {
                diskon = 0;
            }
            return diskon;
        }

        function hitungdiskonSwan() {
            let totalQuantity = calculateTotalQuantityByCategory('D001');
            let diskon = calculateDiscount(totalQuantity, 'D001');
            let diskonbp500 = hitungdiskonProductBP500();
            let diskonSPPP500 = hitungdiskonSPPP500();
            let diskonSPPP1000 = hitungdiskonSPPP1000();
            let diskonSAOSME = hitungdiskonSAOSME();
            let totaldiskon = parseInt(diskon) + parseInt(diskonbp500) + parseInt(diskonSPPP500) + parseInt(
                diskonSPPP1000) + parseInt(
                diskonSAOSME);
            $("#potongan_swan").val(convertToRupiah(totaldiskon));
            return totaldiskon;
        }

        function hitungdiskonStick() {
            let blacklist_pelanggan = [];
            let totalQuantity = calculateTotalQuantityByCategory('D003');
            let diskon = calculateDiscount(totalQuantity, 'D003');

            if (blacklist_pelanggan.includes(kode_pel)) {
                diskon = 0;
            }

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
            let limit_pelanggan = $("#limit_pelanggan").val() == "" ? 0 : $("#limit_pelanggan").val();

            const siklus_pembayaran = $("#siklus_pembayaran").val();
            const max_kredit = $("#max_kredit").val();
            const jenis_transaksi = $("#jenis_transaksi").val();
            const jenis_bayar = $("#jenis_bayar").val();
            const keterangan = $("#keterangan").val();
            const voucher = $("#voucher").val().replace(/\./g, '');
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
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '0' && parseInt(totalPiutang) >
                parseInt(limit_pelanggan)) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (jenis_transaksi == "K" && siklus_pembayaran === '1' && parseInt(grandtotal) >
                parseInt(limit_pelanggan)) {
                SwalWarning('nama_produk', 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !');
                return false;
            } else if (jenis_transaksi == "K" && parseInt(jmlfakturbelumlunas) >= parseInt(
                    jmlmaxfaktur)) {
                SwalWarning('keterangan', 'Melebihi Batas Jumlah Max. Faktur Kredit');
                return false;
            } else if (jenis_transaksi == "K" && sisa_piutang > 0 && keterangan == "") {
                SwalWarning('keterangan', 'Keterangan Harus Diisi !');
                return false;
            } else if (voucher > saldo_voucher) {
                SwalWarning('voucher', 'Melebihi Saldo Voucher !');
                return false;
            } else {
                //return false;
                buttonDisable();
            }
        });

        function generatenofaktur() {
            var tanggal = $("#tanggal").val();
            var kode_salesman = $("#kode_salesman").val();
            buttonDisable();
            $.ajax({
                type: 'POST',
                url: '/penjualan/generatenofaktur',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    buttonEnable();
                    if (respond !== '0') {
                        $("#no_faktur").val(respond);
                        $("#no_faktur").prop('readonly', true);
                    }

                    console.log(respond);
                    // alert(respond);

                }
            });
        }

        $("#tanggal,#kode_salesman").change(function() {
            generatenofaktur();
        });
    });
</script>
@endpush
