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
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col">
                                    @include('dashboard.generalaffair.kir')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    @include('dashboard.generalaffair.pajaksatutahun')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    @include('dashboard.generalaffair.pajaklimatahun')
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Rekap Kendaraan</h4>
                                </div>
                                <div class="card-body">
                                    @include('dashboard.generalaffair.rekapkendaraan')
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-7">
                                        <div class="card-body text-nowrap">
                                            <h5 class="card-title mb-0">Database Kendaraan! 🎉</h5>
                                            <p class="mb-2">Jumlah Kendaraan Aktif</p>
                                            <h4 class="text-primary mb-1">{{ $jmlkendaraan }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-5 text-center text-sm-left">
                                        <div class="card-body pb-0 px-0 px-md-0">
                                            <img src="{{ asset('assets/img/illustrations/truck2.png') }}" height="140" alt="view sales">
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


</div>
@endsection
@push('myscript')
@endpush
