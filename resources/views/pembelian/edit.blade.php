@extends('layouts.app')
@section('titlepage', 'Edit Pembelian')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Pembelian</span> / <span>Edit Pembelian</span>
@endsection
<form action="{{ route('pembelian.update', Crypt::encrypt($pembelian->no_bukti)) }}" method="POST" id="formPembelian">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" value="{{ $pembelian->no_bukti }}" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-datepmb"
                                value="{{ $pembelian->tanggal }}" />
                            <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                                upperCase="true" select2="select2Kodesupplier" selected="{{ $pembelian->kode_supplier }}" />
                            <div class="form-group mb-3">
                                <select name="kode_asal_pengajuan" id="kode_asal_pengajuan" class="form-select" disabled="true">
                                    <option value="">Asal Ajuan</option>
                                    @foreach ($asal_ajuan as $d)
                                        <option value="{{ $d['kode_group'] }}"
                                            {{ $pembelian->kode_asal_pengajuan == $d['kode_group'] ? 'selected' : '' }}>
                                            {{ $d['nama_group'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                                    <option value="">Tunai / Kredit</option>
                                    <option value="T" {{ $pembelian->jenis_transaksi == 'T' ? 'selected' : '' }}>Tunai</option>
                                    <option value="K" {{ $pembelian->jenis_transaksi == 'K' ? 'selected' : '' }}>Kredit</option>
                                </select>
                            </div>
                            <x-input-with-icon label="Jatuh Tempo" name="jatuh_tempo" icon="ti ti-calendar" datepicker="flatpickr-datepmb"
                                value="{{ $pembelian->jatuh_tempo }}" />
                            <div class="form-group mb-3">
                                <small class="text-light fw-medium d-block mb-2 mt-2">PPN</small>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="ppn" id="ppn1" value="1"
                                        {{ $pembelian->ppn == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ppn1">Ya</label>
                                </div>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="ppn" id="ppn2" value="0"
                                        {{ $pembelian->ppn == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ppn2">Tidak</label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <small class="text-light fw-medium d-block mb-2 mt-2">Kategori Transaksi</small>
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio1" value="MP"
                                        {{ $pembelian->kategori_transaksi == 'MP' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">MP</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio2" value="PC"
                                        {{ $pembelian->kategori_transaksi == 'PC' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">Pacific</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio3" value="PB"
                                        {{ $pembelian->kategori_transaksi == 'PB' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio3">Pribadi</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori_transaksi" id="inlineRadio4" value="IP"
                                        {{ $pembelian->kategori_transaksi == 'IP' ? 'checked' : '' }}>
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
                            @if ($cekhistoribayar > 0)
                                <div class="alert alert-warning">
                                    <p>Data Pembelian dengan No. Bukti {{ $pembelian->no_bukti }} Sudah Memiliki Histori Pembayaran, yang sudah
                                        dibayarkan oleh keuangan, Untuk melakukan
                                        Tambah Data Barang Pembelian, ataupun Edit Quantity atau Harga silahkan Hubungi Bagian Keuangan Untuk
                                        melakukan Pembatalan
                                        Pembayaran Terlebih Dahulu</p>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ request()->url() }}" class="btn btn-danger"><i class="ti ti-refresh"></i></a>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Nama Barang" name="nama_barang" icon="ti ti-barcode" readonly="true" />
                                        <input type="hidden" id="kode_barang" name="kode_barang">
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Qty" name="jumlah" icon="ti ti-box" align="right" numberFormat="true" />
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Harga" name="harga" icon="ti ti-moneybag" align="right"
                                            numberFormat="true" />
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
                            @endif

                            <div class="row">
                                <div class="col">

                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%">Kode</th>
                                                <th style="width: 20%">Nama Barang</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                {{-- <th>Subotal</th> --}}
                                                <th>Peny</th>
                                                <th>Total</th>
                                                <th style="width: 20%">kode Akun</th>
                                                <th style="width: 3%">Cabang</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadbarang">
                                            @php
                                                $total_pembelian = 0;
                                                $no = 1;
                                            @endphp
                                            @foreach ($detail as $d)
                                                @php
                                                    $subtotal = $d->jumlah * $d->harga;
                                                    $total = $subtotal + $d->penyesuaian;
                                                    $total_pembelian += $total;
                                                    $bg = '';
                                                    if (!empty($d->kode_cr)) {
                                                        $bg = 'bg-info text-white';
                                                    }
                                                @endphp
                                                <tr class="{{ $bg }}" id="index_{{ $no }}">
                                                    <input type="hidden" name="kode_barang_item[]" value="{{ $d->kode_barang }}"
                                                        class="kode_barang" />
                                                    <input type="hidden" name="jumlah_item[]"
                                                        value="{{ formatAngkaDesimal($d->jumlah) }}"class="jumlah" />
                                                    <input type="hidden" name="harga_item[]" value="{{ formatAngkaDesimal($d->harga) }}"
                                                        class="" />
                                                    <input type="hidden" name="penyesuaian_item[]"
                                                        value="{{ formatAngkaDesimal($d->penyesuaian) }}" class="penyesuaian" />
                                                    <input type="hidden" name="kode_akun_item[]" value="{{ $d->kode_akun }}"
                                                        class="kode_akun" />
                                                    <input type="hidden" name="keterangan_item[]" value="{{ $d->keterangan }}"
                                                        class="keterangan" />
                                                    <input type="hidden" name="kode_cabang_item[]" value="{{ $d->kode_cabang }}"
                                                        class="kode_cabang" />
                                                    <td>{{ $d->kode_barang }}</td>
                                                    <td>{{ textCamelCase($d->nama_barang) }}</td>
                                                    <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                                    <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                                                    {{-- <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td> --}}
                                                    <td class="text-end">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                                                    <td class="text-end totalharga">{{ formatAngkaDesimal($total) }}</td>
                                                    <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                    <td>{{ $d->kode_cabang }}</td>
                                                    <td>
                                                        <div class='d-flex'>
                                                            {{-- @if ($cekhistoribayar === 0) --}}
                                                            <div>
                                                                <a href="#" class="btnEditbarang me-1" id="index_{{ $no }}"><i
                                                                        class="ti ti-edit text-success"></i></a>
                                                            </div>
                                                            {{-- @endif --}}

                                                            <div>
                                                                <a href="#" class="btnSplit me-1" id="index_{{ $no }}">
                                                                    <i class="ti ti-adjustments text-primary"></i>
                                                                </a>
                                                            </div>

                                                            <div>
                                                                <a href="#" class="me-1" data-bs-toggle="popover" data-bs-placement="left"
                                                                    data-bs-html="true" data-bs-content="{{ $d->keterangan }}" title="Keterangan"
                                                                    data-bs-custom-class="popover-info">
                                                                    <i class="ti ti-info-square text-warning"></i>
                                                                </a>
                                                            </div>
                                                            @if ($cekhistoribayar === 0)
                                                                <div>
                                                                    <a href="#" id="index_{{ $no }}" class="delete"><i
                                                                            class="ti ti-trash text-danger"></i></a>
                                                                </div>
                                                            @endif
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
                                                <td colspan="5">TOTAL</td>
                                                <td id="grandtotal" class="text-end"></td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    @if ($cekhistoribayar === 0)
                                        <a href="#" class="btn btn-danger mb-3" id="tambahpotongan"><i class="ti ti-tag me-1"></i>Tambah
                                            Potongan</a>
                                    @endif

                                    <table class="table table-bordered">
                                        <thead class="bg-danger">
                                            <tr>
                                                <th class="text-white">Keterangan</th>
                                                <th class="text-white">Kode Akun</th>
                                                <th class="text-white">Qty</th>
                                                <th class="text-white">Harga</th>
                                                <th class="text-white">Total</th>
                                                <th class="text-white">#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadpotongan">
                                            @php
                                                $no_potongan = 1;
                                            @endphp
                                            @foreach ($potongan as $d)
                                                @php
                                                    $subtotal_potongan = $d->jumlah * $d->harga;
                                                @endphp
                                                <tr id="index_{{ $no_potongan }}">
                                                    <input type="hidden" name="keterangan_potongan_item[]"
                                                        value="{{ $d->keterangan_penjualan }}" />
                                                    <input type="hidden" name="kode_akun_potongan_item[]" value="{{ $d->kode_akun }}" />
                                                    <input type="hidden" name="jumlah_potongan_item[]" value="{{ $d->jumlah }}" />
                                                    <input type="hidden" name="harga_potongan_item[]" value="{{ $d->harga }}" />
                                                    <td>{{ $d->keterangan_penjualan }}</td>
                                                    <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                    <td>{{ formatAngkaDesimal($d->jumlah) }}</td>
                                                    <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                                                    <td class="text-end">{{ formatAngkaDesimal($subtotal_potongan) }}</td>
                                                    <td>
                                                        @if ($cekhistoribayar === 0)
                                                            <a href="#" id="index_{{ $no_potongan }}" class="deletepotongan"><i
                                                                    class="ti ti-trash text-danger"></i></a>
                                                        @endif

                                                    </td>
                                                </tr>
                                                @php
                                                    $no_potongan++;
                                                @endphp
                                            @endforeach
                                        </tbody>
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
<x-modal-form id="modal" size="" show="loadmodal" title="" />
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

        let baris = {{ $no }};
        let barisPotongan = {{ $no_potongan }};
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
            form.find("#penyesuaian").val("");
            form.find('.select2Kodeakun').val('').trigger("change");
            form.find("#keterangan").val("");
            form.find('.select2Kodecabang').val('').trigger("change");

        }


        function resetFormsplit() {
            const formSplit = $(document).find("#formSplitbarang");
            formSplit.find("#kode_barang").val("");
            formSplit.find("#nama_barang_split").val("");
            formSplit.find("#jumlah").val("");
            formSplit.find("#harga").val("");
            formSplit.find("#penyesuaian").val("");
            formSplit.find('.select2Kodeakunsplit').val('').trigger("change");
            formSplit.find("#keterangan").val("");
            formSplit.find('.select2Kodecabangsplit').val('').trigger("change");
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
                jml = numberFormat(jml, '2', ',', '.');
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
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" class="kode_barnag" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" class="jumlah"/>
                    <input type="hidden" name="harga_item[]" value="${harga}" class="harga"/>
                    <input type="hidden" name="penyesuaian_item[]" value="${penyesuaian}" class="penyesuaian"/>
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" class="kode_akun" />
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" class="keterangan"/>
                    <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}"  class="kode_cabang"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jml}</td>
                    <td class='text-end'>${harga}</td>

                    <td class='text-end'>${penyesuaian}</td>
                    <td class='text-end totalharga' >${total}</td>
                    <td>${nama_akun}</td>
                    <td>${kode_cabang}</td>
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
            $("#modal").find("#loadmodal").load(`/pembelian/createpotongan`);
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
            let penyesuaian = currentRow.find('td:eq(4)').text();
            let kode_akun = currentRow.find('.kode_akun').val();
            let keterangan = currentRow.find('.keterangan').val();
            let kode_cabang = currentRow.find('.kode_cabang').val();
            //alert(kode_cabang);
            //alert(status_promosi);
            let dataBarang = {
                'kode_barang': kode_barang,
                'nama_barang': nama_barang,
                'jumlah': jumlah,
                'harga': harga,
                'penyesuaian': penyesuaian,
                'kode_akun': kode_akun,
                'keterangan': keterangan,
                'kode_cabang': kode_cabang,
                'cekhistoribayar': "{{ $cekhistoribayar }}",
            };
            console.log(dataBarang);
            $.ajax({
                type: 'POST',
                url: '/pembelian/editbarang',
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
            const penyesuaian = $(this).find("#penyesuaian").val();
            const dataAkun = $(this).find("#kode_akun_editBarang :selected").select2(this.data);
            const kode_akun = $(dataAkun).val();
            const nama_akun = $(dataAkun).text();
            const keterangan = $(this).find("#keterangan").val();
            const kode_cabang = $(this).find("#kode_cabang_editBarang").val();

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
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_akun_editBarang").focus();
                    },
                });
            } else {
                baris = baris + 1;
                let jml = convertNumber(jumlah);
                let hrg = convertNumber(harga);
                let peny = convertNumber(penyesuaian);
                let subtotal = parseFloat(jml) * parseFloat(hrg);
                let total = parseFloat(subtotal) + parseFloat(peny);
                jml = numberFormat(jml, '2', ',', '.');
                subtotal = numberFormat(subtotal, '2', ',', '.');
                total = numberFormat(total, '2', ',', '.');
                let bg;
                if (kode_akun.substring(0, 3) == '6-1' && kode_cabang != '' || kode_akun.substring(0, 3) == '6-2' && kode_cabang !=
                    '') {
                    bg = "bg-info text-white";
                } else {
                    bg = "";
                }
                let newRow = `
                <tr id="index_${baris}" class="${bg}">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" class="kode_barang"/>
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" class="jumlah"/>
                    <input type="hidden" name="harga_item[]" value="${harga}" class="harga"/>
                    <input type="hidden" name="penyesuaian_item[]" value="${penyesuaian}" class="penyesuaian"/>
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" class="kode_akun" />
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" class="keterangan"/>
                    <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" class="kode_cabang"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jml}</td>
                    <td class='text-end'>${harga}</td>

                    <td class='text-end'>${penyesuaian}</td>
                    <td class='text-end totalharga' >${total}</td>
                    <td>${nama_akun}</td>
                    <td>${kode_cabang}</td>
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


        $(document).on('click', '.btnSplit', function(e) {
            e.preventDefault();
            // Dapatkan baris tabel yang sesuai
            currentRow = $(this).closest('tr');

            // Ambil data dari sel
            let kode_barang = currentRow.find('td:eq(0)').text();
            let nama_barang = currentRow.find('td:eq(1)').text();
            let jumlah = currentRow.find('td:eq(2)').text();
            let harga = currentRow.find('td:eq(3)').text();
            let penyesuaian = currentRow.find('td:eq(4)').text();
            let kode_akun = currentRow.find('.kode_akun').val();
            let keterangan = currentRow.find('.keterangan').val();
            let kode_cabang = currentRow.find('.kode_cabang').val();
            //alert(kode_cabang);
            //alert(status_promosi);
            let dataBarang = {
                'kode_barang': kode_barang,
                'nama_barang': nama_barang,
                'jumlah': jumlah,
                'harga': harga,
                'penyesuaian': penyesuaian,
                'kode_akun': kode_akun,
                'keterangan': keterangan,
                'kode_cabang': kode_cabang
            };
            console.log(dataBarang);
            $.ajax({
                type: 'POST',
                url: '/pembelian/splitbarang',
                data: {
                    _token: "{{ csrf_token() }}",
                    databarang: dataBarang
                },
                cache: false,
                success: function(respond) {
                    $("#modal").modal("show");
                    $("#modal").find(".modal-title").text("Split Barang");
                    $("#loadmodal").html(respond);
                    $("#modal").find(".modal-dialog").addClass("modal-xxl");
                }
            });
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

        $(document).on('click', '#btnSplitbarang', function(e) {
            e.preventDefault();
            const formSplit = $(document).find("#formSplitbarang");
            const kode_barang = formSplit.find("#kode_barang").val();
            const nama_barang = formSplit.find("#nama_barang_split").val();
            const jumlah = formSplit.find("#jumlah").val();
            const harga = formSplit.find("#harga").val();
            const penyesuaian = formSplit.find("#penyesuaian").val();
            const dataAkun = formSplit.find("#kode_akun_split :selected").select2(this.data);
            const kode_akun = $(dataAkun).val();
            const nama_akun = $(dataAkun).text();
            const keterangan = formSplit.find("#keterangan").val();
            const kode_cabang = formSplit.find("#kode_cabang_split").val();
            const totalSplit = convertNumber(formSplit.find('#totalSplit').text());
            const total = parseFloat(convertNumber(jumlah)) * parseFloat(convertNumber(harga)) + parseFloat(convertNumber(
                penyesuaian));
            const jmlSplit = parseFloat(grandTotalsplit) + parseFloat(total);
            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Barang Harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formSplit.find("#nama_barang_split").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formSplit.find("#jumlah").focus();
                    },
                });
            } else if (harga == "" || harga === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formSplit.find("#harga").focus();
                    },
                });
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formSplit.find("#kode_akun_split").focus();
                    },
                });
            } else if (parseFloat(jmlSplit) > parseFloat(totalSplit)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Split Melebihi Total Seharusnya !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formSplit.find("#jumlah").focus();
                    },
                });
            } else {
                barisSplit = barisSplit + 1;
                let jml = convertNumber(jumlah);
                let hrg = convertNumber(harga);
                let peny = convertNumber(penyesuaian);
                let subtotal = parseFloat(jml) * parseFloat(hrg);
                let total = parseFloat(subtotal) + parseFloat(peny);
                jml = numberFormat(jml, '2', ',', '.');
                subtotal = numberFormat(subtotal, '2', ',', '.');
                total = numberFormat(total, '2', ',', '.');
                let bg;
                if (kode_akun.substring(0, 3) == '6-1' && kode_cabang != '' || kode_akun.substring(0, 3) == '6-2' && kode_cabang !=
                    '') {
                    bg = "bg-info text-white";
                } else {
                    bg = "";
                }
                let splitRow = `
                <tr id="index_${barisSplit}" class="${bg}">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" class="kode_barang"/>
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" class="jumlah"/>
                    <input type="hidden" name="harga_item[]" value="${harga}" class="harga"/>
                    <input type="hidden" name="penyesuaian_item[]" value="${penyesuaian}" class="penyesuaian"/>
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" class="kode_akun" />
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" class="keterangan"/>
                    <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" class="kode_cabang"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td class='text-center'>${jml}</td>
                    <td class='text-end'>${harga}</td>

                    <td class='text-end'>${penyesuaian}</td>
                    <td class='text-end totalharga'>${total}</td>
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
                                <a href="#" id="index_${barisSplit}" class="deleteSplit hapussplit"><i class="ti ti-trash text-danger"></i></a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                formSplit.find("#loadsplitbarang").append(splitRow);
                $('[data-bs-toggle="popover"]').popover();
                calculateTotalsplit();
                resetFormsplit();
            }
        });

        $(document).on('click', '.deleteSplit', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            // event.preventDefault();
            // alert(id);
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
                    $(document).find("#formSplitbarang").find("#loadsplitbarang").find(`#${id}`).remove();
                    calculateTotalsplit();
                }
            });
        });
        $(document).on("click", "#nama_barang_split", function(e) {
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

        $(document).on('submit', '#formSplitbarang', function(e) {
            // Ambil semua baris dari tabel A
            e.preventDefault();
            $('.deleteSplit').remove();
            const totalSplit = convertNumber($(this).find('#totalSplit').text());
            if (parseFloat(grandTotalsplit) != parseFloat(totalSplit)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Sama, dengan Item Yang di Split !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jumlah").focus();
                    },
                });
            } else {
                var rows = $(document).find("#loadsplitbarang tr").clone();
                console.log(rows);
                currentRow.replaceWith(rows);
                $('#loadbarang').append(rows);
                $("#modal").modal("hide");
            }
            // $('.hapussplit').addClass('delete');

        });
    });
</script>
@endpush
