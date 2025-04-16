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

    <div class="alert alert-info">
        @if ($bank != null)
            <h5 class="mb-0 me-2">{{ textUpperCase($bank->nama_bank) }} {{ $bank->no_rekening }}</h5>
        @else
            <h5 class="mb-0 me-2">Semua Rekening</h5>
        @endif

        <span style="font-size: 14px">Periode {{ formatIndo($dari) }} s/d {{ formatIndo($sampai) }}</span>
    </div>
@endsection
<div class="row mt-3">
    <div class="col">
        <div class="card  border-1  border-success">
            <div class="card-body d-flex justify-content-between align-items-center p-3">
                <div class="card-title mb-0">
                    <h5 class="mb-0 me-2">{{ $rekap ? formatRupiah($rekap->rekap_kredit) : 0 }}</h5>
                    <small>Total Kredit</small>
                </div>
                <div class="card-icon">
                    <span class="badge bg-label-success rounded-pill p-2">
                        <i class="ti ti-arrow-down ti-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <div class="card  border-1  border-danger">
            <div class="card-body d-flex justify-content-between align-items-center p-3">
                <div class="card-title mb-0">
                    <h5 class="mb-0 me-2">{{ $rekap ? formatRupiah($rekap->rekap_debet) : 0 }}</h5>
                    <small>Total Debet</small>
                </div>
                <div class="card-icon">
                    <span class="badge bg-label-danger rounded-pill p-2">
                        <i class="ti ti-arrow-up ti-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <form action="{{ URL::current() }}" method="GET">
            <div class="form-group">
                <select name="debet_kredit" id="debet_kredit" class="form-select">
                    <option value="">Debet / Kredit</option>
                    <option value="D">Debet</option>
                    <option value="K">Kredit</option>
                </select>
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
