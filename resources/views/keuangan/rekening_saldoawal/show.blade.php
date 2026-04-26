@extends('layouts.app')
@section('titlepage', 'Detail Saldo Awal Rekening')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Detail Saldo Awal Rekening</h4>
            <small class="text-muted">Rincian saldo awal harian per rekening bank.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('samutasibank.index') }}">Saldo Awal Rekening</a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Detail Header dalam card seperti index filter --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th style="width: 30%;">Kode Saldo</th>
                                <td>: <span class="fw-bold">{{ $saldo_awal->kode_saldo_awal }}</span></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>: {{ formatIndo($saldo_awal->tanggal) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel dalam card dengan header seperti index --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-eye me-2"></i>Rincian Saldo Awal Rekening</h6>
                    <a href="{{ route('samutasibank.index') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">NO</th>
                                <th class="text-white text-center" style="background-color: #002e65 !important;">NAMA BANK / REKENING</th>
                                <th class="text-white text-center" style="background-color: #002e65 !important; width: 40%;">SALDO AWAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($saldo_awal->details as $d)
                                @php $total += $d->jumlah; @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $d->bank->nama_bank }}</div>
                                        <small class="text-muted">{{ $d->bank->no_rekening }}</small>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        {{ formatAngka($d->jumlah) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-center fw-bold">TOTAL</th>
                                <th class="text-end text-primary fw-bold" style="font-size: 1.1rem;">
                                    Rp {{ formatAngka($total) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
