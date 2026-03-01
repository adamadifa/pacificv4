@extends('layouts.app')
@if (request()->is('samutasibank'))
    @section('titlepage', 'Saldo Awal Mutasi Bank')
@else
    @section('titlepage', 'Saldo Awal Ledger')
@endif
@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                @if (request()->is('samutasibank'))
                    Saldo Awal Mutasi Bank
                @else
                    Saldo Awal Ledger
                @endif
            </h4>
            <small class="text-muted">Manajemen saldo awal bulanan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-database-import me-1"></i>Saldo Awal</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @if (request()->is('samutasibank'))
                @include('layouts.navigation_mutasibank')
            @else
                @include('layouts.navigation_ledger')
            @endif
        </div>

        {{-- Filter Section --}}
        <form action="{{ URL::current() }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_bank_search" id="kode_bank_search" class="form-select select2Kodebanksearch">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($bank as $d)
                                        <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}
                                            value="{{ $d->kode_bank }}">{{ $d->nama_bank }}
                                            {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                            value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                            @else
                                            {{ date('Y') == $t ? 'selected' : '' }} @endif
                                            value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-database-import me-2"></i>Data Saldo Awal</h6>
                    @canany(['saledger.create', 'samutasibank.create'])
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Buat Saldo Awal
                        </a>
                    @endcanany
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">NO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">BULAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TAHUN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">BANK</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saldo_awal as $d)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center fw-bold">{{ $d->kode_saldo_awal }}</td>
                                <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                                <td class="text-center">{{ $nama_bulan[$d->bulan] }}</td>
                                <td class="text-center">{{ $d->tahun }}</td>
                                <td>{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatAngka($d->jumlah) }}</td>
                                <td class="text-center">
                                    @can('saledger.delete')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('saledger.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </a>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Saldo Awal Ledger");
            $("#loadmodal").load(`/saledger/create`);
        });

        const select2Kodebanksearch = $('.select2Kodebanksearch');
        if (select2Kodebanksearch.length) {
            select2Kodebanksearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
