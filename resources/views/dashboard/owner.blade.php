@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <div class="row">
                    @foreach ($bank as $d)
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <a href="{{ route('mutasikeuangan.show', Crypt::encrypt($d->kode_bank)) }}">
                                <div class="card h-100 border-1 ">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-0 me-2">{{ formatRupiah($d->saldo) }}</h5>
                                            <small>{{ $d->nama_bank }}</small>
                                            <br>
                                            <span class="fw-semibold">{{ $d->no_rekening }}</span>
                                        </div>
                                        <div class="card-icon">
                                            <span class="badge bg-label-primary rounded-pill p-2">
                                                <i class="ti ti-cpu ti-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
