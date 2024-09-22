@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>
            <div class="tab-content" id="tab-content-main">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Rekap Penjualan</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" id="formRekappenjualan">
                                        @hasanyrole($roles_show_cabang)
                                            <x-select label="Cabang" name="kode_cabang_rekappenjualan" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" select2="select2Kodecabangrekappenjualan" upperCase="true" />
                                        @endhasanyrole
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100" id="btnRekappenjualan"><i
                                                    class="ti ti-eye me-1"></i>Tampilkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Analisa Umur Piutang</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" id="formAup">
                                        @hasanyrole($roles_show_cabang)
                                            <x-select label="Cabang" name="kode_cabang_aup" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                select2="select2KodecabangAup" upperCase="true" />
                                        @endhasanyrole
                                        <x-input-with-icon label="Lihat per Tanggal" name="tanggal" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                        <div class="form-group mb-3">
                                            <select name="exclude" id="exclude" class="form-select">
                                                <option value="1">Exclude Pusat</option>
                                                <option value="2">Include Pusat</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100" id="btnAup">
                                                <i class="ti ti-eye me-1"></i>Tampilkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Rekap DPPP</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" id="formDppp">
                                        @hasanyrole($roles_show_cabang)
                                            <x-select label="Cabang" name="kode_cabang_rekapdppp" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" select2="select2Kodecabangrekapdppp" upperCase="true" />
                                        @endhasanyrole
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100" id="btnRekapdppp"><i class="ti ti-eye me-1"></i>Tampilkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Rekap Kendaraan</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" id="formRekapkendaraan">
                                        @hasanyrole($roles_show_cabang)
                                            <x-select label="Cabang" name="kode_cabang_rekapkendaraan" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" select2="select2Kodecabangrekapkendaraan" upperCase="true" />
                                        @endhasanyrole
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100" id="btnRekapkendaraan"><i
                                                    class="ti ti-eye me-1"></i>Tampilkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12" id="loadrekappersediaan"></div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td></td>
                                                <td>STOK AMAN</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-danger"></td>
                                                <td>Stok Kurang Dari Buffer</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-info"></td>
                                                <td>Stok Lebih dari Max. Stok</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="{{ route('ajuanlimit.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}">
                                        <div class="card card-border-shadow-primary h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded bg-label-primary"><i
                                                                class="ti ti-brand-shopee ti-28px"></i></span>
                                                    </div>
                                                    <h4 class="mb-0">{{ $notifikasi_limitkredit }}</h4>
                                                </div>
                                                <p class="mb-1">Ajuan Limit Kredit</p>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="{{ route('ajuanfaktur.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}">
                                        <div class="card card-border-shadow-warning h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded bg-label-warning"><i
                                                                class="ti ti-file-invoice ti-28px"></i></span>
                                                    </div>
                                                    <h4 class="mb-0">{{ $notifikasi_ajuanfaktur }}</h4>
                                                </div>
                                                <p class="mb-1">Ajuan Faktur Kredit</p>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="{{ route('penilaiankaryawan.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}">
                                        <div class="card card-border-shadow-info h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded bg-label-info"><i
                                                                class="ti ti-users ti-28px"></i></span>
                                                    </div>
                                                    <h4 class="mb-0">{{ $notifikasi_penilaiankaryawan }}</h4>
                                                </div>
                                                <p class="mb-1">Penilaian Karyawan</p>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="{{ route('izinabsen.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}">
                                        <div class="card card-border-shadow-info h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded bg-label-info"><i
                                                                class="ti ti-receipt ti-28px"></i></span>
                                                    </div>
                                                    <h4 class="mb-0">{{ $notifikasi_pengajuan_izin }}</h4>
                                                </div>
                                                <p class="mb-1">Pengajuan Izin</p>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="{{ route('lembur.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}">
                                        <div class="card card-border-shadow-info h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded bg-label-info"><i
                                                                class="ti ti-clock ti-28px"></i></span>
                                                    </div>
                                                    <h4 class="mb-0">{{ $notifikasi_lembur }}</h4>
                                                </div>
                                                <p class="mb-1">Lembur</p>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card card-border-shadow-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar me-4">
                                                    <span class="avatar-initial rounded bg-label-success"><i
                                                            class="ti ti-target-arrow ti-28px"></i></span>
                                                </div>
                                                <h4 class="mb-0">{{ $notifikasi_target }}</h4>
                                            </div>
                                            <p class="mb-1">Ajuan Target Marketing</p>

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
<x-modal-form id="modal" show="loadmodal" title="" size="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const formRekappenjualan = $('#formRekappenjualan');
        const formDppp = $('#formDppp');
        const formRekapkendaraan = $('#formRekapkendaraan');
        const formAup = $('#formAup');

        const select2Kodecabangrekappenjualan = $('.select2Kodecabangrekappenjualan');
        if (select2Kodecabangrekappenjualan.length) {
            select2Kodecabangrekappenjualan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodecabangAup = $('.select2KodecabangAup');
        if (select2KodecabangAup.length) {
            select2KodecabangAup.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabangrekapkendaraan = $('.select2Kodecabangrekapkendaraan');
        if (select2Kodecabangrekapkendaraan.length) {
            select2Kodecabangrekapkendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabangrekapdppp = $('.select2Kodecabangrekapdppp');
        if (select2Kodecabangrekapdppp.length) {
            select2Kodecabangrekapdppp.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }

        formRekappenjualan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
            const bulan = formRekappenjualan.find('#bulan').val();
            const tahun = formRekappenjualan.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekappenjualan.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekappenjualan.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Penjualan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekappenjualan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        formRekapkendaraan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekapkendaraan.find('#kode_cabang_rekapkendaraan').val();
            const bulan = formRekapkendaraan.find('#bulan').val();
            const tahun = formRekapkendaraan.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekapkendaraan.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekapkendaraan.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Kendaraan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapkendaraan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        formDppp.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formDppp.find('#kode_cabang_rekapdppp').val();
            const bulan = formDppp.find('#bulan').val();
            const tahun = formDppp.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formDppp.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formDppp.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('DPPP');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapdppp') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        })

        formAup.submit(function(e) {
            e.preventDefault();
            const kode_cbg = "{{ auth()->user()->kode_cabang }}";
            const kode_cabang = kode_cbg != 'PST' ? kode_cbg : formAup.find('#kode_cabang_aup').val();
            // alert(kode_cabang);
            const tanggal = formAup.find('#tanggal').val();
            const exclude = formAup.find('#exclude').val();
            const address = kode_cabang == "" ? "/dashboard/rekapaup" : "/dashboard/rekapaupcabang";
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formAup.find('#tanggal').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('AUP');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: address,
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        kode_cabang: kode_cabang,
                        exclude: exclude
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        })

        function loadrekappersediaan() {
            const level_user = "{{ $level_user }}";
            // if (level_user == "direktur" || level_user == "super admin" || level_user == "gm marketing" || level_user == "gm administrasi") {
            //     $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     </div>`);
            //     $("#loadrekappersediaan").load('/dashboard/rekappersediaan');
            // }
            $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaan');

        }

        loadrekappersediaan();
    });
</script>
@endpush
