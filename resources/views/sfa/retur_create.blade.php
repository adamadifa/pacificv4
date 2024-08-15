@extends('layouts.app')
@section('titlepage', 'Input Retur')
@section('content')
    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Retur</span> / <span>Input Retur</span>
@endsection
<form action="{{ route('retur.store') }}" method="POST" id="formRetur">
    @csrf
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Retur" name="no_retur" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
                                value="{{ date('Y-m-d') }}" />
                            <x-input-with-icon label="Pelanggan" name="nama_pelanggan" icon="ti ti-user" readonly="true" />
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                            <x-input-with-icon label="Salesman" name="nama_salesman" icon="ti ti-user" readonly="true" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman">
                            <div class="form-group mb-3">
                                <select name="jenis_retur" id="jenis_retur" class="form-select">
                                    <option value="">Jenis Retur</option>
                                    <option value="GB">Ganti Barang</option>
                                    <option value="PF">Potongan Faktur</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <select name="no_faktur" id="no_faktur" class="form-select">
                                    <option value="">Pilih Faktur</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none">
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
                            <h5 class="card-title">Detail Retur</h5>
                        </div>
                        <div class="card-body ">
                            <div class="row d-none">
                                <div class="col-lg-6 col-md-12 col-sm12">
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
                                        <table class="table" id="tabelproduk">
                                            {{-- <thead class="text-center table-dark">
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
                                            </thead> --}}
                                            <tbody id="loadproduk"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td class="text-end" id="subtotal"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
                                            value="" id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
                                    </div>
                                    <div class="form-group mb-3" id="saveButton">
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
</form>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modaleditProduk" size="" show="loadmodaleditProduk" title="" />

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
                        getListfaktur(response.data.kode_pelanggan);
                        //open modal
                        $('#modalPelanggan').modal('hide');
                        buttonEnable();
                    }

                }
            });
        }

        getPelanggan("{{ Crypt::encrypt($kode_pelanggan) }}");
        //Pilih Pelanggan
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
            $("#loadproduk").html('');
            $("#potongan_swan").val(0);
            $("#potongan_aida").val(0);
            $("#potongan_sp").val(0);
            $("#potongan_stick").val(0);
            $("#potongan_sambal").val(0);
            loadsubtotal();

        });

        //GetProduk
        function getHarga(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/harga/${kode_pelanggan}/gethargareturbypelanggan`,
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




        //Tambah Item Produk
        $("#tambahproduk").click(function(e) {
            e.preventDefault();

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


        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            //addProduk();
            const kode_pelanggan = "{{ Crypt::encrypt($kode_pelanggan) }}"
            $("#modaleditProduk").modal("show");
            $("#modaleditProduk").find(".modal-title").text("Tambah Produk");
            $("#loadmodaleditProduk").load(`/sfa/retur/${kode_pelanggan}/addproduk`);
        });

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
                        $(this).find("#kode_harga").focus();
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
            $("#grandtotal_text").text(convertToRupiah(subtotal));
        }

        function getListfaktur(kode_pelanggan) {
            $.ajax({
                type: 'POST',
                url: '/penjualan/getfakturbypelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: kode_pelanggan
                },
                cache: false,
                success: function(respond) {
                    $("#no_faktur").html(respond);
                }
            });
        }
        $("#formRetur").submit(function(e) {
            const no_retur = $("#no_retur").val();
            const tanggal = $("#tanggal").val();
            const kode_pelanggan = $("#kode_pelanggan").val();
            const kode_salesman = $("#kode_salesman").val();
            const jenis_retur = $("#jenis_retur").val();
            const no_faktur = $("#no_faktur").val();
            if (tanggal == '') {
                SwalWarning('tanggal', 'Tanggal Tidak Boleh Kosong');
                return false;
            } else if (kode_pelanggan == "") {
                SwalWarning('nama_pelanggan', 'Pelanggan Tidak Boleh Kosong');
                return false;
            } else if (kode_salesman == "") {
                SwalWarning('nama_salesman', 'Salesman Tidak Boleh Kosong');
                return false;
            } else if (jenis_retur == "") {
                SwalWarning('jenis_retur', 'Jenis Retur Tidak Boleh Kosong');
                return false;
            } else if (no_faktur == "") {
                SwalWarning('no_faktur', 'No. Faktur Tidak Boleh Kosong');
                return false;
            } else if ($('#loadproduk tr').length == 0) {
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else {
                buttonDisable();
            }
        });

        $("#saveButton").hide();

        $('.agreement').change(function() {
            //  alert('test');
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });
    });
</script>
@endpush
