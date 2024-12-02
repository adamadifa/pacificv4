@extends('layouts.app')
@section('titlepage', 'Input Pembelian')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Pembelian</span> / <span>Input Pembelian</span>
@endsection
<form action="{{ route('pembelian.store') }}" method="POST" id="formPembelian">
    @csrf
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-datepmb" />
                            <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                                upperCase="true" select2="select2Kodesupplier" />
                            <div class="form-group mb-3">
                                <select name="kode_asal_pengajuan" id="kode_asal_pengajuan" class="form-select">
                                    <option value="">Asal Ajuan</option>
                                    @foreach ($asal_ajuan as $d)
                                        <option value="{{ $d['kode_group'] }}">
                                            {{ $d['nama_group'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                                    <option value="">Tunai / Kredit</option>
                                    <option value="T">Tunai</option>
                                    <option value="K">Kredit</option>
                                </select>
                            </div>
                            <x-input-with-icon label="Jatuh Tempo" name="jatuh_tempo" icon="ti ti-calendar" datepicker="flatpickr-datepmb" />
                            <div class="form-group mb-3">
                                <small class="text-light fw-medium d-block mb-2 mt-2">PPN</small>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="ppn" id="ppn1" value="1">
                                    <label class="form-check-label" for="ppn1">Ya</label>
                                </div>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="ppn" id="ppn2" value="0" checked>
                                    <label class="form-check-label" for="ppn2">Tidak</label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <small class="text-light fw-medium d-block mb-2 mt-2">Kategori Transaksi</small>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio1" value="MP">
                                    <label class="form-check-label" for="inlineRadio1">MP</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio2" value="PC">
                                    <label class="form-check-label" for="inlineRadio2">Pacific</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio3" value="PB">
                                    <label class="form-check-label" for="inlineRadio3">Pribadi</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio4" value="IP">
                                    <label class="form-check-label" for="inlineRadio4">IP</label>
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
                                    <x-input-with-icon label="Nama Barang" name="nama_barang" icon="ti ti-barcode" readonly="true" />
                                    <input type="hidden" id="kode_barang" name="kode_barang">
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Qty" name="jumlah" icon="ti ti-box" align="right" numberFormat="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Harga" name="harga" icon="ti ti-moneybag" align="right" numberFormat="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Penyesuaian" name="penyesuaian" icon="ti ti-moneybag" align="right"
                                        numberFormat="true" />
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                                            <option value="">Akun</option>
                                            @foreach ($coa as $d)
                                                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        upperCase="true" select2="select2Kodecabang" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnTambahbarang">
                                            <i class="ti ti-plus me-1"></i>Tambah Barang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">

                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%">Kode</th>
                                                <th style="width: 20%">Nama Barang</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Subotal</th>
                                                <th>Peny</th>
                                                <th>Total</th>
                                                <th style="width: 15%">kode Akun</th>
                                                <th>Cabang</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadbarang"></tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <td colspan="6">TOTAL</td>
                                                <td id="grandtotal" class="text-end"></td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
                                            value="" id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
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
<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
        let baris = 0;
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
            form.find("#penyesuaian").val("");
            form.find('.select2Kodeakun').val('').trigger("change");
            form.find("#keterangan").val("");
            form.find('.select2Kodecabang').val('').trigger("change");

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

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Cabang',
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
            let kode_group = form.find("#kode_asal_pengajuan").val();
            if (kode_group == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Pengajuan Harus Diisi Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_asal_pengajuan").focus();
                    },
                });
            } else {
                loadTablebarang(kode_group);
                $("#modalBarang").modal("show");
            }
        });

        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');
            form.find("#kode_barang").val(kode_barang);
            form.find("#nama_barang").val(nama_barang);
            $("#modalBarang").modal("hide");
            form.find("#qty").focus();

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

        function addBarang() {
            const kode_barang = form.find("#kode_barang").val();
            const nama_barang = form.find("#nama_barang").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const penyesuaian = form.find("#penyesuaian").val();
            const dataAkun = form.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataAkun).val();
            const nama_akun = $(dataAkun).text();
            const keterangan = form.find("#keterangan").val();
            const kode_cabang = form.find("#kode_cabang").val();


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
            } else if (kode_akun == "") {
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
                let peny = convertNumber(penyesuaian);
                let subtotal = parseFloat(jml) * parseFloat(hrg);
                let total = parseFloat(subtotal) + parseFloat(peny);
                subtotal = numberFormat(subtotal, '2', ',', '.');
                total = numberFormat(total, '2', ',', '.');
                let bg;
                if (kode_akun.substring(0, 3) == '6-1' && kode_cabang != '' || kode_akun.substring(0, 3) == '6-2' && kode_cabang != '') {
                    bg = "bg-info text-white";
                } else {
                    bg = "";
                }
                let barang = `
                <tr id="index_${baris}" class="${bg}">
                    <td>
                        <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" />
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                        <input type="hidden" name="harga_item[]" value="${harga}" />
                        <input type="hidden" name="penyesuaian_item[]" value="${penyesuaian}" />
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                        <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" />
                        ${kode_barang}
                    </td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jumlah}</td>
                    <td class='text-end'>${harga}</td>
                    <td class='text-end'>${subtotal}</td>
                    <td class='text-end'>${penyesuaian}</td>
                    <td class='text-end totalharga' >${total}</td>
                    <td>${nama_akun}</td>
                    <td>${kode_cabang}</td>
                    <td>
                        <div class='d-flex'>
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
                    $(`#${id}`).remove();
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
            const kode_asal_pengajuan = form.find("#kode_asal_pengajuan").val();
            const jenis_transaksi = form.find("#jenis_transaksi").val();
            const jatuh_tempo = form.find("#jatuh_tempo").val();

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
            } else if (kode_asal_pengajuan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Ajuan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_asal_pengajuan").focus();
                    },
                });
                return false;
            } else if (jenis_transaksi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Transaksi harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jenis_transaksi").focus();
                    },
                });
                return false;
            } else if (jatuh_tempo == "" && jenis_transaksi == 'K') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jatuh Tempo harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jatuh_tempo").focus();
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

    });
</script>
@endpush
