@extends('layouts.app')
@section('titlepage', 'Kontrabon Pembelian')

@section('content')
@section('navigasi')
    <span>Kontrabon Pembelian</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('kontrabonpmb.create')
                        <a href="{{ route('kontrabonpmb.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                            Kontra Bon</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @include('pembelian.kontrabon.kontrabon')
            </div>
        </div>
    </div>
</div>

@endsection
