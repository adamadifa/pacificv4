@extends('layouts.app')
@section('titlepage', 'Edit PO')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">PO</span> / <span>Edit PO</span>
@endsection
<form action="{{ route('po.update', Crypt::encrypt($po->no_bukti)) }}" method="POST" id="formPembelian">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode"
                                value="{{ $po->no_bukti }}" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar"
                                datepicker="flatpickr-datepmb" value="{{ $po->tanggal }}" />
                            <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier"
                                textShow="nama_supplier" upperCase="true" select2="select2Kodesupplier"
                                selected="{{ $po->kode_supplier }}" />
                            <div class="form-group mb-3">
                                <small class="text-light fw-medium d-block mb-2 mt-2">Kategori Perusahaan</small>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="kategori_perusahaan"
                                        id="inlineRadio1" value="MP"
                                        {{ $po->kategori_perusahaan == 'MP' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">MP</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_perusahaan"
                                        id="inlineRadio2" value="CVMP"
                                        {{ $po->kategori_perusahaan == 'CVMP' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">CV MP</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">

            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">Detail Pembelian</h5>
                                <div class="d-flex justify-content-between">
                                    <i class="ti ti-shopping-cart text-primary me-5" style="font-size: 2em;"></i>
                                    <h4 id="grandtotal_text">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Nama Barang" name="nama_barang" icon="ti ti-barcode"
                                        readonly="true" />
                                    <input type="hidden" id="kode_barang" name="kode_barang">
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Qty" name="jumlah" icon="ti ti-box" align="right"
                                        numberFormat="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Harga" name="harga" icon="ti ti-moneybag"
                                        align="right" numberFormat="true" />
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Keterangan" name="keterangan"
                                        icon="ti ti-file-description" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnTambahbarang">
                                            <i class="ti ti-plus me-1"></i>Tambah Barang
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <a class="btn btn-danger w-100" id="btnReset" href="{{ request()->url() }}">
                                            <i class="ti ti-refresh me-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">

                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 5%">Kode</th>
                                                <th style="width: 20%">Nama Barang</th>
                                                <th style="width: 5%">Qty</th>
                                                <th style="width: 8%">Harga</th>
                                                <th style="width: 10%">Total</th>
                                                <th style="width: 7%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadbarang">
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($detail as $d)
                                                @php
                                                    $subtotal = $d->jumlah * $d->harga;
                                                    $bg = '';
                                                @endphp
                                                <tr class="{{ $bg }}" id="index_{{ $no }}">
                                                    <input type="hidden" name="kode_barang_item[]"
                                                        value="{{ $d->kode_barang }}" class="kode_barang" />
                                                    <input type="hidden" name="jumlah_item[]"
                                                        value="{{ formatAngkaDesimal($d->jumlah) }}"class="jumlah" />
                                                    <input type="hidden" name="harga_item[]"
                                                        value="{{ formatAngkaDesimal($d->harga) }}" class="" />
                                                    <input type="hidden" name="keterangan_item[]"
                                                        value="{{ $d->keterangan }}" class="keterangan" />
                                                    <td>{{ $d->kode_barang }}</td>
                                                    <td>{{ textCamelCase($d->nama_barang) }}</td>
                                                    <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                                    <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                                                    <td class="text-end totalharga">
                                                        {{ formatAngkaDesimal($subtotal) }}
                                                    </td>
                                                    <td>
                                                        <div class='d-flex'>
                                                            <div>
                                                                <a href="#" class="btnEditbarang me-1"
                                                                    id="index_{{ $no }}"><i
                                                                        class="ti ti-edit text-success"></i></a>
                                                            </div>
                                                            <div>
                                                                <a href="#" class="me-1"
                                                                    data-bs-toggle="popover" data-bs-placement="left"
                                                                    data-bs-html="true"
                                                                    data-bs-content="{{ $d->keterangan }}"
                                                                    title="Keterangan"
                                                                    data-bs-custom-class="popover-info">
                                                                    <i class="ti ti-info-square text-warning"></i>
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a href="#" id="index_{{ $no }}"
                                                                    class="delete"><i
                                                                        class="ti ti-trash text-danger"></i></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                    $no += 1;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <td colspan="4">TOTAL</td>
                                                <td id="grandtotal" class="text-end"></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input agreement" name="aggrement" value="aggrement"
                                            type="checkbox" value="" id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ?
                                        </label>
                                    </div>
                                    <div class="form-group" id="saveButton">
                                        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                                            <ion-icon name="send-outline" class="me-1"></ion-icon>
                                            Submit
                                        </button>
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
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelbarang" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jenis Barang</th>
                                <th>Kategori</th>
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
<script>
    $(document).ready(function() {
        const form = $("#formPembelian");

        let baris = {{ $no }};
        let barisSplit = 1;
        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        form.find("#no_bukti").on('keydown keyup', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
            }
            this.value = this.value.toUpperCase();
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..
            `);
        }

        function resetForm() {
            form.find("#kode_barang").val("");
            form.find("#nama_barang").val("");
            form.find("#jumlah").val("");
            form.find("#harga").val("");
            form.find("#keterangan").val("");

        }


        function resetFormsplit() {
            const formSplit = $(document).find("#formSplitbarang");
            formSplit.find("#kode_barang").val("");
            formSplit.find("#nama_barang_split").val("");
            formSplit.find("#jumlah").val("");
            formSplit.find("#harga").val("");
            formSplit.find("#keterangan").val("");
        }


        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Supplier',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loadTablebarang(kode_group = "000") {

            $('#tabelbarang').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                ajax: `/barangpembelian/${kode_group}/getbarangjson`,
                bAutoWidth: false,
                bDestroy: true,
                columns: [{
                        data: 'kode_barang',
                        name: 'kode_barang',
                        orderable: true,
                        searchable: true,
                        width: '10%'
                    },
                    {
                        data: 'namabarang',
                        name: 'nama_barang',
                        orderable: true,
                        searchable: true,
                        width: '40%'
                    },
                    {
                        data: 'satuan',
                        name: 'satuan',
                        orderable: true,
                        searchable: false,
                        width: '10%'
                    },

                    {
                        data: 'jenisbarang',
                        name: 'jenisbarang',
                        orderable: true,
                        searchable: false,
                        width: '20%'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        orderable: true,
                        searchable: false,
                        width: '20%'
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

                }
            });
        }


        $("#nama_barang").click(function(e) {
            loadTablebarang(kode_group);
            $("#modalBarang").modal("show");
        });

        function isModalOpen(modalId) {
            var modal = document.getElementById(modalId);
            if (modal) {
                return modal.classList.contains('show');
            }
            return false;
        }

        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');

            if (!isModalOpen('modal')) {
                form.find("#kode_barang").val(kode_barang);
                form.find("#nama_barang").val(nama_barang);
                form.find("#qty").focus();
            } else {
                $(document).find("#formSplitbarang").find("#nama_barang_split").val(nama_barang);
                $(document).find("#formSplitbarang").find("#kode_barang").val(kode_barang);
            }


            $("#modalBarang").modal("hide");

        });

        function convertNumber(number) {
            // Hilangkan semua titik
            let formatted = number.replace(/\./g, '');
            // Ganti semua koma dengan titik
            formatted = formatted.replace(/,/g, '.');
            return formatted || 0;
        }

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        };

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }
        calculateTotal();

        function addBarang() {
            const kode_barang = form.find("#kode_barang").val();
            const nama_barang = form.find("#nama_barang").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const keterangan = form.find("#keterangan").val();


            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Barang Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nama_barang").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
            } else if (harga == "" || harga === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun").focus();
                    },
                });
            } else {
                baris = baris + 1;
                let jml = convertNumber(jumlah);
                let hrg = convertNumber(harga);
                let subtotal = parseFloat(jml) * parseFloat(hrg);
                let total = parseFloat(subtotal);
                jml = numberFormat(jml, '2', ',', '.');
                subtotal = numberFormat(subtotal, '2', ',', '.');
                total = numberFormat(total, '2', ',', '.');
                let bg;
                let barang = `
                <tr id="index_${baris}" class="${bg}">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" class="kode_barnag" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" class="jumlah"/>
                    <input type="hidden" name="harga_item[]" value="${harga}" class="harga"/>
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" class="keterangan"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jml}</td>
                    <td class='text-end'>${harga}</td>
                    <td class='text-end totalharga' >${total}</td>
                    <td>
                        <div class='d-flex'>
                            <div>
                                <a href="#" class="btnEditbarang me-1" id="index_${baris}"><i class="ti ti-edit text-success"></i></a>
                            </div>
                            <div>
                                <a href="#" class="btnSplit me-1"  id="index_${baris}"><i class="ti ti-adjustments text-primary"></i></a>
                            </div>
                            <div>
                                <a href="#" class="me-1" data-bs-toggle="popover"
                                    data-bs-placement="left" data-bs-html="true"
                                    data-bs-content="${keterangan}" title="Keterangan"
                                    data-bs-custom-class="popover-info">
                                    <i class="ti ti-info-square text-warning"></i>
                                </a>
                            </div>
                            <div>
                                <a href="#" id="index_${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                $('#loadbarang').append(barang);
                $('[data-bs-toggle="popover"]').popover();
                calculateTotal();
                resetForm();
            }
        }

        form.find("#btnTambahbarang").click(function(e) {
            e.preventDefault();
            addBarang();
        });

        $("#kode_asal_pengajuan").change(function() {
            resetForm();
            $("#loadbarang").html("");
            calculateTotal();
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            // event.preventDefault();
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
                    $("#loadbarang").find(`#${id}`).remove();
                    calculateTotal();
                }
            });
        });


        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function() {
            const no_bukti = form.find("#no_bukti").val();
            const tanggal = form.find("#tanggal").val();
            const kode_supplier = form.find("#kode_supplier").val();

            if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Pembelian harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#no_bukti").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_supplier == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Supplier harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_supplier").focus();
                    },
                });
                return false;
            } else if ($('#loadbarang tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Detail Pembelian Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#nama_barang").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        form.find("#tambahpotongan").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Potongan");
            $("#modal").find("#loadmodal").load(`/po/createpotongan`);
            $("#modal").find(".modal-dialog").removeClass("modal-xxl");
        });




        $(document).on('submit', '#formPotongan', function(e) {
            e.preventDefault();
            const keterangan = $(this).find("#keterangan_potongan").val();
            const jumlah = $(this).find("#jumlah_potongan").val();
            const harga = $(this).find("#harga_potongan").val();
            const total_potongan = $(this).find("#total_potongan").val();
            const dataAkun = $(this).find("#kode_akun_potongan :selected").select2(this.data);
            const kode_akun = $(dataAkun).val();
            const nama_akun = $(dataAkun).text();
            if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#keterangan_potongan").focus();
                    },
                });

                return false;
            } else if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#jumlah_potongan").focus();
                    },
                });

                return false;
            } else if (harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#harga_potongan").focus();
                    },
                });

                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_akun_potongan").focus();
                    },
                });

                return false;
            } else {
                barisPotongan += 1;
                $(this).find("#btnPotongan").html(`
                    <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Loading..
                `);
                let potongan = `
                <tr id="index_${barisPotongan}">
                    <input type="hidden" name="keterangan_potongan_item[]" value="${keterangan}" />
                    <input type="hidden" name="kode_akun_potongan_item[]" value="${kode_akun}" />
                    <input type="hidden" name="jumlah_potongan_item[]" value="${jumlah}" />
                    <input type="hidden" name="harga_potongan_item[]" value="${harga}" />
                    <td>${keterangan}</td>
                    <td>${nama_akun}</td>
                    <td>${jumlah}</td>
                    <td class='text-end'>${harga}</td>
                    <td class='text-end'>${total_potongan}</td>
                    <td>
                        <a href="#" id="index_${barisPotongan}" class="deletepotongan"><i class="ti ti-trash text-danger"></i></a>
                    </td>
                </tr>
                `;
                $('#loadpotongan').append(potongan);
                $("#modal").modal("hide");
            }
        });


        $(document).on('click', '.deletepotongan', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            // event.preventDefault();
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
                    $("#loadpotongan").find(`#${id}`).remove();
                    // calculateTotal();
                }
            });
        });

        let currentRow;
        $(document).on('click', '.btnEditbarang', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_barang = currentRow.find('td:eq(0)').text();
            let nama_barang = currentRow.find('td:eq(1)').text();
            let jumlah = currentRow.find('td:eq(2)').text();
            let harga = currentRow.find('td:eq(3)').text();
            let keterangan = currentRow.find('.keterangan').val();
            let dataBarang = {
                'kode_barang': kode_barang,
                'nama_barang': nama_barang,
                'jumlah': jumlah,
                'harga': harga,
                'keterangan': keterangan,
            };
            console.log(dataBarang);
            $.ajax({
                type: 'POST',
                url: '/po/editbarang',
                data: {
                    _token: "{{ csrf_token() }}",
                    databarang: dataBarang
                },
                cache: false,
                success: function(respond) {
                    $("#modal").modal("show");
                    $("#modal").find(".modal-title").text("Edit Barang");
                    $("#loadmodal").html(respond);
                    $("#modal").find(".modal-dialog").removeClass("modal-xxl");
                }
            });
        });

        $(document).on('submit', '#formEditbarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).find("#kode_barang").val();
            const nama_barang = $(this).find("#nama_barang").val();
            const jumlah = $(this).find("#jumlah").val();
            const harga = $(this).find("#harga").val();
            const keterangan = $(this).find("#keterangan").val();

            if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#jumlah").focus();
                    },
                });
            } else if (harga == "" || harga === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#harga").focus();
                    },
                });
            } else {
                baris = baris + 1;
                let jml = convertNumber(jumlah);
                let hrg = convertNumber(harga);
                let subtotal = parseFloat(jml) * parseFloat(hrg);
                let total = parseFloat(subtotal);
                jml = numberFormat(jml, '2', ',', '.');
                subtotal = numberFormat(subtotal, '2', ',', '.');
                total = numberFormat(total, '2', ',', '.');
                let bg;

                let newRow = `
                <tr id="index_${baris}" class="${bg}">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" class="kode_barang"/>
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" class="jumlah"/>
                    <input type="hidden" name="harga_item[]" value="${harga}" class="harga"/>
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" class="keterangan"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jml}</td>
                    <td class='text-end'>${harga}</td>
                    <td class='text-end totalharga' >${total}</td>
                    <td>
                        <div class='d-flex'>
                            <div>
                                <a href="#" class="btnEditbarang me-1" id="index_${baris}"><i class="ti ti-edit text-success"></i></a>
                            </div>
                            <div>
                                <a href="#" class="btnSplit me-1" id="index_{{ $no }}"><i class="ti ti-adjustments text-primary"></i></a>
                            </div>
                            <div>
                                <a href="#" class="me-1" data-bs-toggle="popover"
                                    data-bs-placement="left" data-bs-html="true"
                                    data-bs-content="${keterangan}" title="Keterangan"
                                    data-bs-custom-class="popover-info">
                                    <i class="ti ti-info-square text-warning"></i>
                                </a>
                            </div>
                            <div>
                                <a href="#" id="index_${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                currentRow.replaceWith(newRow);
                $("#modal").modal("hide");
                $('[data-bs-toggle="popover"]').popover();
                calculateTotal();
            }
        });


        let grandTotalsplit = 0;

        function calculateTotalsplit() {
            const formSplit = $(document).find("#formSplitbarang");
            let grandTotal = 0;
            formSplit.find('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            formSplit.find('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            grandTotalsplit = grandTotal;
            //$('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }


    });
</script>
@endpush
