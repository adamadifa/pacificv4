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
        {{-- @include('dashboard.welcome') --}}

        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-brand-shopee ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">{{ formatRupiah($penjualan->total) }}</h4>
                        </div>
                        <p class="mb-1">Penjualan Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-success"><i class="ti ti-brand-shopee ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">{{ formatRupiah($pembayaran->total) }}</h4>
                        </div>
                        <p class="mb-1">Pembayaran Bulan Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-brand-shopee ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">{{ formatRupiah($jmltransaksi) }}</h4>
                        </div>
                        <p class="mb-1">Jumlah Transaksi</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="nav-align-top">
                <ul class="nav nav-tabs nav-fill rounded-0 timeline-indicator-advanced" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#target"
                            aria-controls="target" aria-selected="false" tabindex="-1">Target</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#histori"
                            aria-controls="histori" aria-selected="true">Histori</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#dpb"
                            aria-controls="dpb" aria-selected="false" tabindex="-1">DPB</button>
                    </li>
                </ul>
                <div class="tab-content border-0  mx-1">
                    <div class="tab-pane fade active show" id="target" role="tabpanel">
                        <form action="#" id="formTarget">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </form>
                        <div class="mt-3" id="loadtarget"></div>
                    </div>
                    <div class="tab-pane fade" id="histori" role="tabpanel">
                        <form action="#" id="formHistori">
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
                                value="{{ date('Y-m-d') }}" />
                        </form>
                        <div class="mt-3" id="loadhistori">

                        </div>
                    </div>
                    <div class="tab-pane fade" id="dpb" role="tabpanel">
                        <form action="#" id="formDpb">
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
                                value="{{ date('Y-m-d') }}" />
                        </form>
                        <div class="mt-3" id="loaddpb"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        const formTarget = $('#formTarget');
        const formHistori = $('#formHistori');
        const formDpb = $('#formDpb');

        function loadtarget() {
            const bulan = formTarget.find("#bulan").val();
            const tahun = formTarget.find("#tahun").val();
            $.ajax({
                type: "POST",
                url: "/targetkomisi/gettargetsalesmandashboard",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadtarget").html(respond);
                }
            });
        }


        function loadhistori() {
            const tanggal = formHistori.find("#tanggal").val();

            $.ajax({
                type: "POST",
                url: "/dashboard/getcheckinsalesman",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $("#loadhistori").html(respond);
                }
            });
        }


        function loaddpb() {
            const tanggal = formDpb.find("#tanggal").val();
            $.ajax({
                type: "POST",
                url: "/dashboard/getdpbsalesman",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $("#loaddpb").html(respond);
                }
            });
        }

        loadhistori();
        loadtarget();
        loaddpb();

        formTarget.find("#bulan,#tahun").change(function() {
            loadtarget();
        });

        formHistori.find("#tanggal").change(function() {
            loadhistori();
        });

        formDpb.find("#tanggal").change(function() {
            loaddpb();
        });

    });
</script>
@endpush
