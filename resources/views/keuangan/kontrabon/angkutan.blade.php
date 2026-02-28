@extends('layouts.app')
@section('titlepage', 'Kontrabon Angkutan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kontrabon Angkutan</h4>
            <small class="text-muted">Mengelola data kontrabon angkutan keuangan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-text me-1"></i>Kontrabon Angkutan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kontrabon')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @include('gudangjadi.kontrabon.kontrabon')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
