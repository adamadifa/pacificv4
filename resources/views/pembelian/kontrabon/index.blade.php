@extends('layouts.app')
@section('titlepage', 'Kontrabon Pembelian')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kontrabon Pembelian</h4>
            <small class="text-muted">Manajemen data kontra bon pembelian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pembelian.index') }}"><i class="ti ti-shopping-cart-plus me-1"></i>Pembelian</a>
                </li>
                <li class="breadcrumb-item active">Kontrabon</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        @include('pembelian.kontrabon.kontrabon')
    </div>
</div>
@endsection
