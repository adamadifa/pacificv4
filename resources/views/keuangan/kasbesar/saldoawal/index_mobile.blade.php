@extends('layouts.app')
@section('titlepage', 'Saldo Awal Kas Besar')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="mb-0 fw-bold text-slate-800">Saldo Awal Kas Besar</h4>
            <small class="text-muted" style="font-size: 11px;">Manajemen saldo awal kas besar per periode (Mobile)</small>
        </div>
    </div>
@endsection

<style>
    /* Scrollable Horizontal Sub-navigation */
    .mobile-subnav-container {
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
        margin-bottom: 1rem;
    }
    .mobile-subnav-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    .mobile-subnav-container .nav-pills-custom {
        display: inline-flex;
        flex-wrap: nowrap;
    }
    .mobile-subnav-container .nav-link {
        padding: 0.6rem 1rem !important;
        font-size: 12px;
    }

    /* Filter Chevron Rotation */
    .transition-rotate {
        transition: transform 0.25s ease-in-out;
    }
    [aria-expanded="true"] .transition-rotate {
        transform: rotate(185deg);
    }

    /* Floating Action Button (FAB) */
    .fab-btn {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #002e65 0%, #004b93 100%) !important;
        border: none !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(0, 46, 101, 0.3) !important;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .fab-btn:active {
        transform: scale(0.9) !important;
        box-shadow: 0 5px 10px rgba(0, 46, 101, 0.4) !important;
    }

    /* Premium Mobile Cards */
    .saldo-card {
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 16px !important;
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .saldo-card:active {
        transform: scale(0.99);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }

    /* Grid Value blocks */
    .val-block {
        border-radius: 10px;
        padding: 8px 10px;
        border: none !important;
    }
    .bg-kertas {
        background-color: rgba(0, 46, 101, 0.06) !important;
    }
    .bg-logam {
        background-color: rgba(67, 138, 243, 0.08) !important;
    }
    .bg-transfer {
        background-color: rgba(40, 199, 111, 0.08) !important;
    }
    .bg-giro {
        background-color: rgba(255, 159, 67, 0.08) !important;
    }
</style>

<div class="row">
    <div class="col-12">
        {{-- Navigation (Horizontal Scrollable on Mobile) --}}
        <div class="mobile-subnav-container">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Collapsible Filter Section --}}
        <div class="card border mb-3 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 bg-light cursor-pointer" 
                 data-bs-toggle="collapse" 
                 href="#collapseFilter" 
                 role="button" 
                 aria-expanded="false" 
                 aria-controls="collapseFilter">
                <span class="fw-bold text-slate-700" style="font-size: 13px;">
                    <i class="ti ti-filter me-2 text-primary fs-5"></i>Filter Pencarian
                </span>
                <i class="ti ti-chevron-down text-muted transition-rotate"></i>
            </div>
            <div class="collapse {{ Request('bulan') || Request('tahun') ? 'show' : '' }}" id="collapseFilter">
                <div class="card-body p-3 border-top bg-white">
                    <form action="{{ route('sakasbesar.index') }}">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <select name="bulan" id="bulan" class="form-select text-slate-700" style="font-size: 13px;">
                                        <option value="">Bulan</option>
                                        @foreach ($list_bulan as $d)
                                            <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <select name="tahun" id="tahun" class="form-select text-slate-700" style="font-size: 13px;">
                                        <option value="">Tahun</option>
                                        @for ($t = $start_year; $t <= date('Y'); $t++)
                                            <option
                                                @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                @else
                                                {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                value="{{ $t }}">{{ $t }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary w-100 py-2" style="border-radius: 8px;">
                                    <i class="ti ti-search me-1"></i>Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Data Card List --}}
        @if ($saldo_awal->isEmpty())
            {{-- Empty State --}}
            <div class="card border shadow-sm rounded-3 py-5 px-3 text-center my-4">
                <div class="mb-3 text-muted">
                    <i class="ti ti-wallet-off" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-slate-800" style="font-size: 15px;">Tidak Ada Data Saldo Awal</h5>
                <p class="text-muted mb-0" style="font-size: 12px;">Silakan tentukan filter bulan/tahun atau tambahkan saldo awal baru.</p>
            </div>
        @else
            <div class="saldo-card-list">
                @foreach ($saldo_awal as $d)
                    @php
                        $total_saldo = $d->uang_kertas + $d->uang_logam + $d->transfer + $d->giro;
                    @endphp
                    <div class="card saldo-card mb-3 overflow-hidden">
                        {{-- Header: Branch and Month/Year --}}
                        <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background-color: #fcfdfe;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 10px; border-radius: 6px;">{{ textUpperCase($d->nama_cabang) }}</span>
                                <span class="text-slate-500" style="font-size: 11px;">{{ $d->kode_saldo_awal }}</span>
                            </div>
                            <div>
                                <span class="fw-bold text-slate-800" style="font-size: 12px;">{{ $nama_bulan[$d->bulan] }} {{ $d->tahun }}</span>
                            </div>
                        </div>

                        {{-- Body: Values & Total --}}
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded-3">
                                <span class="text-muted fw-medium" style="font-size: 11px;">Total Saldo Awal</span>
                                <span class="fw-bold text-primary" style="font-size: 15px;">Rp {{ formatAngka($total_saldo) }}</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="val-block bg-kertas">
                                        <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #002e65; font-weight: 500;">
                                            <i class="ti ti-cash fs-6" style="color: #002e65;"></i>Uang Kertas
                                        </div>
                                        <div class="fw-bold text-slate-800 mt-1" style="font-size: 12px;">Rp {{ formatAngka($d->uang_kertas) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="val-block bg-logam">
                                        <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #438af3; font-weight: 500;">
                                            <i class="ti ti-coin fs-6" style="color: #438af3;"></i>Uang Logam
                                        </div>
                                        <div class="fw-bold text-slate-800 mt-1" style="font-size: 12px;">Rp {{ formatAngka($d->uang_logam) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="val-block bg-transfer">
                                        <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #28c76f; font-weight: 500;">
                                            <i class="ti ti-send fs-6" style="color: #28c76f;"></i>Transfer
                                        </div>
                                        <div class="fw-bold text-slate-800 mt-1" style="font-size: 12px;">Rp {{ formatAngka($d->transfer) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="val-block bg-giro">
                                        <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #ff9f43; font-weight: 500;">
                                            <i class="ti ti-file-description fs-6" style="color: #ff9f43;"></i>Giro
                                        </div>
                                        <div class="fw-bold text-slate-800 mt-1" style="font-size: 12px;">Rp {{ formatAngka($d->giro) }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer: Date and Delete Action --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 11px;">
                                    <i class="ti ti-calendar fs-6"></i> {{ date('d-m-Y', strtotime($d->tanggal)) }}
                                </div>
                                <div>
                                    @can('sakasbesar.delete')
                                        <form method="POST" class="deleteform d-inline"
                                              action="{{ route('sakasbesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger delete-confirm"
                                                    style="border-radius: 8px;" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- FAB (Floating Action Button) for Create --}}
@can('sakasbesar.create')
    <a href="#" class="fab-btn" id="btnCreate">
        <i class="ti ti-plus fs-3"></i>
    </a>
@endcan

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Saldo Awal Kas Besar");
            $("#loadmodal").load(`/sakasbesar/create`);
        });
    });
</script>
@endpush
