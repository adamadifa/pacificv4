@extends('layouts.app')
@section('titlepage', 'Dashboard General Affair')
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

@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        .premium-card {
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 24px;
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #002e65 0%, #0056b3 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            @include('dashboard.welcome')

            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    @include('layouts.navigation_dashboard')
                </ul>

                <div class="tab-content mt-3" id="tab-content-main">
                    <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                        <div class="row">
                            {{-- Left Column: Alerts & Tables --}}
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <div class="card premium-card shadow-sm mb-4">
                                    <div class="card-body">
                                        @include('dashboard.generalaffair.kir')
                                    </div>
                                </div>
                                <div class="card premium-card shadow-sm mb-4">
                                    <div class="card-body">
                                        @include('dashboard.generalaffair.pajaksatutahun')
                                    </div>
                                </div>
                                <div class="card premium-card shadow-sm mb-4">
                                    <div class="card-body">
                                        @include('dashboard.generalaffair.pajaklimatahun')
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Stats & Rekap --}}
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                {{-- Database Overview Card --}}
                                <div class="card premium-card bg-gradient-blue text-white mb-4">
                                    <div class="card-body position-relative py-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="stat-icon-wrapper bg-white bg-opacity-20 me-3">
                                                <i class="ti ti-truck"></i>
                                            </div>
                                            <h5 class="card-title text-white mb-0">Database Kendaraan</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-8">
                                                <h2 class="text-white fw-bold mb-1">{{ number_format($jmlkendaraan) }}</h2>
                                                <p class="text-white text-opacity-75 mb-0">Total Kendaraan Aktif</p>
                                            </div>
                                            <div class="col-4 text-end">
                                                <img src="{{ asset('assets/img/illustrations/truck2.png') }}"
                                                    style="height: 100px; width: auto; position: absolute; right: 10px; bottom: 10px; opacity: 0.8;"
                                                    alt="truck illustration">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Rekap Card --}}
                                <div class="card premium-card shadow-sm mb-4">
                                    <div class="card-header border-bottom py-3">
                                        <h5 class="card-title mb-0"><i class="ti ti-layout-grid me-2 text-primary"></i>Rekap per Cabang</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        @include('dashboard.generalaffair.rekapkendaraan')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


</div>
@endsection
@push('myscript')
@endpush
