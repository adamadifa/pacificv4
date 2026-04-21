@extends('layouts.app')
@section('titlepage', 'Harga HPP')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Harga HPP</h4>
            <small class="text-muted">Kelola data Harga Awal HPP (Accounting).</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-description me-1"></i>Harga Awal HPP</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_hpp')
        </div>

        {{-- Filter Section (Outside Card) --}}
        <form action="{{ route('hargaawalhpp.store') }}" method="POST" id="formHargaAwal" aria-autocomplete="off">
            @csrf
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 mb-2">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group mb-3">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option {{ Request('tahun') == $t ? 'selected' : '' }} value="{{ $t }}">
                                            {{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="lokasi" id="lokasi" class="form-select select2lokasi">
                                    <option value="">Pilih Lokasi</option>
                                    <option value="GDG">GUDANG</option>
                                    @foreach ($cabang as $d)
                                        <option {{ Request('lokasi') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                            {{ textUpperCase($d->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Card --}}
            <div class="card shadow-sm border mt-2">
                <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-description me-2"></i>Input Harga Awal HPP</h6>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr style="background-color: #002e65;">
                                <th class="py-3 text-white" style="padding-left: 15px;">KODE</th>
                                <th class="py-3 text-white" style="width: 50%">NAMA PRODUK</th>
                                <th class="py-3 text-white">HARGA</th>
                            </tr>
                        </thead>
                        <tbody id="loadhargaawal" class="table-border-bottom-0">
                            {{-- Content loaded via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 col-md-12">
                    <button class="btn btn-primary w-100 mb-2" id="btnSimpan"><i class="ti ti-send me-1"></i>UPDATE DATA</button>
                </div>
                <div class="col-lg-6 col-md-12">
                    <button class="btn btn-success w-100" type="button" id="btnGenerateHarga"><i class="ti ti-refresh me-1"></i>GENERATE HARGA</button>
                </div>
            </div>
        </form>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`);
        }

        const select2lokasi = $('.select2lokasi');
        if (select2lokasi.length) {
            select2lokasi.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Lokasi',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loadhargaawal() {
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const lokasi = $('#lokasi').val();
            if (bulan && tahun && lokasi) {
                $.ajax({
                    url: `/hargaawalhpp/gethargaawal`,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        lokasi: lokasi
                    },
                    success: function(respond) {
                        $("#loadhargaawal").html(respond);
                    }
                });
            }
        }

        $("#bulan,#tahun,#lokasi").change(function() {
            loadhargaawal();
        });

        loadhargaawal();

        $("#formHargaAwal").on('submit', function(e) {
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const lokasi = $('#lokasi').val();

            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            } else if (lokasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Lokasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }
            buttonDisable();
        });

        $("#btnGenerateHarga").click(function(e) {
            e.preventDefault();
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const lokasi = $('#lokasi').val();

            if (bulan == "" || tahun == "" || lokasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan, Tahun, dan Lokasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }

            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda Yakin Ingin Generate Harga dari Rekap BJ Bulan Sebelumnya?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Generate!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#btnGenerateHarga").prop('disabled', true);
                    $("#btnGenerateHarga").html(`<div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>`);

                    $.ajax({
                        url: `/hargaawalhpp/generateharga`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bulan: bulan,
                            tahun: tahun,
                            lokasi: lokasi
                        },
                        success: function(respond) {
                            $("#btnGenerateHarga").prop('disabled', false);
                            $("#btnGenerateHarga").html(`<i class="ti ti-refresh me-1"></i>GENERATE HARGA`);

                            // Update input values
                            $(".kode_produk").each(function() {
                                const kode_produk = $(this).val();
                                if (respond[kode_produk] !== undefined) {
                                    const harga = respond[kode_produk];
                                    // Cari input harga_awal di row yang sama
                                    $(this).closest('tr').find('input[name="harga_awal[]"]').val(harga);
                                }
                            });

                            // Re-init number separator
                            easyNumberSeparator({
                                selector: '.number-separator',
                                separator: '.',
                                decimalSeparator: ',',
                            });

                            Swal.fire({
                                title: "Berhasil!",
                                text: "Harga Berhasil Di-generate. Silakan Periksa dan Klik UPDATE DATA untuk Menyimpan.",
                                icon: "success",
                                showConfirmButton: true
                            });
                        },
                        error: function() {
                            $("#btnGenerateHarga").prop('disabled', false);
                            $("#btnGenerateHarga").html(`<i class="ti ti-refresh me-1"></i>GENERATE HARGA`);
                            Swal.fire({
                                title: "Error!",
                                text: "Gagal Generate Harga. Pastikan Data Rekap BJ Bulan Sebelumnya Sudah Lengkap.",
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
