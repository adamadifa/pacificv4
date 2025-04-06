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
    <h5>{{ textUpperCase($bank->nama_bank) }} {{ $bank->no_rekening }}</h5>
@endsection
<div class="row">
    <div class="col">
        <form action="{{ route('mutasikeuangan.show', Crypt::encrypt($bank->kode_bank)) }}" method="GET">
            <div class="row">
                <div class="col-md-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary w-100" id="showButton"><i class="ti ti-heart-rate-monitor me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <div class="row">
                @if ($mutasi->isEmpty())
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-body">
                                <strong>Belum ada data</strong>
                            </div>
                        </div>
                    </div>
                @endif
                @foreach ($mutasi as $d)
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div class="card  border-1 {{ $d->debet_kredit == 'D' ? 'border-danger' : 'border-success' }}">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0 me-2">{{ formatRupiah($d->jumlah) }}</h5>
                                    <small>{{ $d->keterangan }}</small>
                                    <br>
                                    <small class="fw-semibold text-sm-center">{{ DateToIndo($d->tanggal) }}</small>
                                </div>
                                <div class="card-icon">
                                    <span class="badge {{ $d->debet_kredit == 'D' ? 'bg-label-danger' : 'bg-label-success' }} rounded-pill p-2">
                                        @if ($d->debet_kredit == 'D')
                                            <i class="ti ti-arrow-up ti-sm"></i>
                                        @else
                                            <i class="ti ti-arrow-down ti-sm"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
