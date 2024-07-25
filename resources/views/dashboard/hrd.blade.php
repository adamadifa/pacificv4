@extends('layouts.app')
@section('titlepage', 'Dashboard HRD')
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
                    <div class="row mb-3">
                        <div class="col">
                            @include('dashboard.hrd.rekapkaryawan')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12">
                            @include('dashboard.hrd.rekapkontrak')
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Karyawan Cabang</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Cabang</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($karyawancabang as $d)
                                                <tr>
                                                    <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                                    <td class="text-center">{{ $d->jml_karyawancabang }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
