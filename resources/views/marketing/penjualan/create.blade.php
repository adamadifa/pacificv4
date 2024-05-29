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
<form action="{{ route('penjualan.cetaksuratjalanrange') }}" target="_blank" method="POST" id="formCetakfaktur">
    <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
    <input type="hidden" name="sisa_piutang" id="sisa_piutang">
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Faktur" name="no_faktur" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                            <x-input-with-icon label="Pelanggan" name="nama_pelanggan" icon="ti ti-user"
                                readonly="true" />
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                            <x-input-with-icon label="Salesman" name="nama_salesman" icon="ti ti-user"
                                readonly="true" />
                            <input type="hidden" name="kode_salesman">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
                                <div class="icon-cart mt-3">
                                    <i class="ti ti-shopping-bag text-primary" style="font-size: 8rem"></i>
                                </div>
                                <div class="mt-2">
                                    <h1 style="font-size: 6.5rem">0</h1>
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
                                    <x-input-with-icon label="Produk" name="nama_produk" icon="ti ti-barcode"
                                        height="80px" readonly="true" />
                                    <input type="hidden" id="kode_harga" name="kode_harga">
                                    <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                                    <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
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
                                            class="ti ti-plus me-1"></i>Tambah Produk</a>
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
                                            <tbody id="loadproduk"></tbody>
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
                                            <x-input-with-group label="AIDA" placeholder="Potongan AIDA"
                                                name="potongan_aida" align="right" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan SWAN"
                                                name="potongan_swan" align="right" />
                                            <x-input-with-group label="STICK" placeholder="Potongan STICK"
                                                name="potongan_stick" align="right" />
                                            <x-input-with-group label="SAMBAL" placeholder="Potongan SAMBAL"
                                                name="potpngan_sambal" align="right" />
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
                                            <x-input-with-group label="AIDA" placeholder="Potongan Istimewa AIDA"
                                                name="potis_aida" align="right" />
                                            <x-input-with-group label="SWAN" placeholder="Potongan Istimewa SWAN"
                                                name="potis_swan" align="right" />
                                            <x-input-with-group label="STICK" placeholder="Potongan Istimewa STICK"
                                                name="potis_stick" align="right" />
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
                                            <x-input-with-group label="AIDA" placeholder="Penyesuaian AIDA"
                                                name="peny_aida" align="right" />
                                            <x-input-with-group label="SWAN" placeholder="Penyesuaian SWAN"
                                                name="peny_swan" align="right" />
                                            <x-input-with-group label="STICK" placeholder="Penyesuaian STICK"
                                                name="peny_stick" align="right" />
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
                                                <select name="jenis_transaksi" id="jenis_transaksi"
                                                    class="form-select">
                                                    <option value="">Jenis Transaksi</option>
                                                    <option value="T">TUNAI</option>
                                                    <option value="K">KREDIT</option>
                                                </select>
                                            </div>
                                            <x-input-with-icon label="Grand Total" name="grandtotal"
                                                icon="ti ti-shopping-cart" align="right" />
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
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
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

        $("#nama_pelanggan").on('click focus', function(e) {
            e.preventDefault();
            $("#modalPelanggan").modal("show");
        });



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
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#sisa_piutang_text").text(convertToRupiah(response.data));
                    $("#sisa_piutang").text(response.data);
                }
            });
        }


        function getFakturkredit(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getFakturkredit`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    console.log(response);
                    $("#jmlfaktur_kredit").text(convertToRupiah(response.data));
                }
            });
        }

        //Get Pelanggan
        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    $('#kode_pelanggan').val(response.data.kode_pelanggan);
                    $('#nama_pelanggan').val(response.data.nama_pelanggan);
                    $('#latitude').text(response.data.latitude);
                    $('#longitude').text(response.data.longitude);
                    $('#no_hp_pelanggan').text(response.data.no_hp_pelanggan);
                    $('#limit_pelanggan_text').text(convertToRupiah(response.data.limit_pelanggan));
                    $('#limit_pelanggan').text(response.data.limit_pelanggan);
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
                }
            });
        }
        //Pilih Pelanggan
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
        });


        //GetProduk
        function getHarga(kode_pelanggan) {
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargabypelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
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
            let nama_produk = $(this).attr('nama_produk');
            let harga_dus = $(this).attr('harga_dus');
            let harga_pack = $(this).attr('harga_pack');
            let harga_pcs = $(this).attr('harga_pcs');

            let harga_dus_produk = $(this).attr('harga_dus');
            let harga_pack_produk = $(this).attr('harga_pack');
            let harga_pcs_produk = $(this).attr('harga_pcs');

            let isi_pcs_dus = $(this).attr('isi_pcs_dus');
            let isi_pcs_pack = $(this).attr('isi_pcs_pack');
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

            //Disabled Harga
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                $("#jml_pack").prop('disabled', true);
            } else {
                $("#jml_pack").prop('disabled', false);
            }
            $("#harga_dus").prop('disabled', true);
            $("#harga_pack").prop('disabled', true);
            $("#harga_pcs").prop('disabled', true);
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
                            <input type="hidden" name="kode_harga[]" value="${kode_harga}"/>
                            <input type="hidden" name="status_promosi[]" class="status_promosi" value="${status_promosi}"/>
                            ${kode_harga}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">${dus===0 ? '' : dus}</td>
                        <td class="text-end">${harga_dus}</td>
                        <td class="text-center">${pack===0 ? '' :pack}</td>
                        <td class="text-end">${harga_pack}</td>
                        <td class="text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="text-end">${harga_pcs}</td>
                        <td class="text-end">${convertToRupiah(subtotal)}</td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div me-2>
                                 <a href="#" key="${index}" class="edit"><i class="ti ti-edit text-success"></i></a>
                              </div>
                              <div>
                                 <a href="#" key="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                              </div>
                           </div>
                            
                        </td>
                    </tr>
                `;

                //append to table
                $('#loadproduk').prepend(produk);
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
                }
            });
        });
        let currentRow;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_harga = currentRow.find('td:eq(0)').text();
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
            let index_old = kode_harga + status_promosi;
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

        $(document).on('submit', '#formEditproduk', function(e) {
            e.preventDefault();
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
                            <input type="hidden" name="kode_harga[]" value="${kode_harga}"/>
                            ${kode_harga}
                        </td>
                        <td>${nama_produk}</td>
                        <td class="text-center">${dus===0 ? '' : dus}</td>
                        <td class="text-end">${harga_dus}</td>
                        <td class="text-center">${pack===0 ? '' :pack}</td>
                        <td class="text-end">${harga_pack}</td>
                        <td class="text-center">${pcs===0 ? '' :pcs}</td>
                        <td class="text-end">${harga_pcs}</td>
                        <td class="text-end">${convertToRupiah(subtotal)}</td>
                        <td class="text-center">
                           <div class="d-flex">
                              <div me-2>
                                 <a href="#" key="${index}" class="edit"><i class="ti ti-edit text-success"></i></a>
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
            } else {
                currentRow.replaceWith(newRow);
                $("#modaleditProduk").modal("hide");
            }

        });
    });
</script>
@endpush
