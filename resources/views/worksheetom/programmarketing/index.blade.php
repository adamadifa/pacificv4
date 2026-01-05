@extends('layouts.app')

@section('title', 'Program Marketing')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Program Marketing
        </h4>

        <div class="row">
            <!-- 2025 Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all bg-primary text-white">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ti ti-calendar-event fs-1 mb-3 text-white"></i>
                        <h1 class="display-3 fw-bold text-white mb-3">2025</h1>
                        <p class="text-white opacity-75 mb-4">Program Marketing Tahun 2025</p>
                        <a href="{{ route('ajuanprogramikatan.index') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary shadow-sm stretched-link">
                            Lihat Program
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2026 Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all bg-success text-white">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ti ti-calendar-event fs-1 mb-3 text-white"></i>
                        <h1 class="display-3 fw-bold text-white mb-3">2026</h1>
                        <p class="text-white opacity-75 mb-4">Program Marketing Tahun 2026</p>
                        <a href="{{ route('programikatan2026.index') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-success shadow-sm stretched-link">
                            Lihat Program
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
        }
        .transition-all {
            transition: all .3s ease-in-out;
        }
    </style>
@endsection
