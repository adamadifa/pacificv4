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
    <span>Dashboard</span>
@endsection
<div class="row">
    <div class="col-xl-12">
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
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Rekap Kendaraan</h4>
                                </div>
                                <div class="card-body">
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


</div>
@endsection
@push('myscript')
@endpush
