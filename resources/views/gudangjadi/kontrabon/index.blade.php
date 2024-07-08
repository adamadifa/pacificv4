@extends('layouts.app')
@section('titlepage', 'Kontrabon Angkutan')

@section('content')
@section('navigasi')
    <span>Kontrabon Angkutan</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('kontrabonangkutan.create')
                        <a href="{{ route('kontrabonangkutan.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                            Kontra Bon</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @include('gudangjadi.kontrabon.kontrabon');
            </div>
        </div>
    </div>
</div>

@endsection
