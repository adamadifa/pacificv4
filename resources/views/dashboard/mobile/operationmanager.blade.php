@extends('layouts.app')
@section('titlepage', 'Dashboard')
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
    <div class="col-12 mb-4">
        <div class="card text-white overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #294C9A 0%, #1E3A70 100%);">
            <div class="card-body p-4 relative z-10">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar me-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=fff&color=294c9a" class="rounded-circle" alt="Profile" style="width: 45px; height: 45px;">
                    </div>
                    <div>
                        <p class="mb-0 text-white-50 small text-uppercase tracking-wider">Selamat Datang</p>
                        <h4 class="mb-0 text-white font-semibold">{{ auth()->user()->name }}</h4>
                    </div>
                </div>
                <p class="mb-0 text-white-50 text-xs">
                    Siap untuk mengelola performa hari ini? Pantau metrik pemasaran Anda dan capai target lebih efisien.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
