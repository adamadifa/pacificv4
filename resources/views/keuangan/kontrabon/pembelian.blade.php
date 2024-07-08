@extends('layouts.app')
@section('titlepage', 'Kontrabon Pembelian')

@section('content')
@section('navigasi')
    <span>Kontrabon Pembelian</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kontrabon')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @include('pembelian.kontrabon.kontrabon')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
