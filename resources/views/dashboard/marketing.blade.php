@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
@section('navigasi')
    <span>Dashboard</span>
@endsection
<div class="row">
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">

                </div>

            </div>
        </div>
    </div>


</div>
@endsection
