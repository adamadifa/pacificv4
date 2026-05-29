@extends('layouts.app')
@section('titlepage', 'Setoran Pusat')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="mb-0 fw-bold text-slate-800">Setoran Pusat</h4>
            <small class="text-muted" style="font-size: 11px;">Manajemen setoran kas ke pusat (Mobile View)</small>
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
    .setoran-card {
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 16px !important;
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .setoran-card:active {
        transform: scale(0.99);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }

    /* Status Badges */
    .bg-label-success {
        background-color: rgba(40, 199, 111, 0.1) !important;
        color: #28c76f !important;
    }
    .bg-label-warning {
        background-color: rgba(255, 159, 67, 0.1) !important;
        color: #ff9f43 !important;
    }
    .bg-label-danger {
        background-color: rgba(234, 84, 85, 0.1) !important;
        color: #ea5455 !important;
    }

    /* Custom Input Height & Font for Mobile */
    .flatpickr-date {
        font-size: 13px !important;
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
            <div class="collapse {{ Request('dari') || Request('sampai') || Request('kode_cabang_search') ? 'show' : '' }}" id="collapseFilter">
                <div class="card-body p-3 border-top bg-white">
                    <form action="{{ route('setoranpusat.index') }}">
                        <div class="row g-2">
                            <div class="col-6">
                                <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" hideLabel="true" placeholder="Dari Tanggal" />
                            </div>
                            <div class="col-6">
                                <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" hideLabel="true" placeholder="Sampai Tanggal" />
                            </div>
                            @hasanyrole($roles_show_cabang)
                                <div class="col-12">
                                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                        select2="select2Kodecabangsearch" hideLabel="true" />
                                </div>
                            @endrole
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
        @if ($setoran_pusat->isEmpty())
            {{-- Empty State --}}
            <div class="card border shadow-sm rounded-3 py-5 px-3 text-center my-4">
                <div class="mb-3 text-muted">
                    <i class="ti ti-cash-off" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-slate-800" style="font-size: 15px;">Tidak Ada Data Setoran</h5>
                <p class="text-muted mb-0" style="font-size: 12px;">Silakan tentukan filter tanggal atau tambahkan setoran baru.</p>
            </div>
        @else
            <div class="setoran-card-list">
                @foreach ($setoran_pusat as $d)
                    <div class="card setoran-card mb-3 overflow-hidden">
                        {{-- Header: Branch and Date --}}
                        <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background-color: #fcfdfe;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 10px; border-radius: 6px;">{{ $d->kode_cabang }}</span>
                                <span class="text-slate-800 fw-bold" style="font-size: 12px; letter-spacing: 0.2px;">{{ date('d-m-Y', strtotime($d->tanggal)) }}</span>
                            </div>
                            <div>
                                @if ($d->status == '1')
                                    <span class="badge bg-label-success fw-bold" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-check me-1 fs-6"></i>Diterima</span>
                                @elseif($d->status == '2')
                                    <span class="badge bg-label-danger fw-bold" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-x me-1 fs-6"></i>Ditolak</span>
                                @else
                                    <span class="badge bg-label-warning fw-bold" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-hourglass-empty me-1 fs-6"></i>Pending</span>
                                @endif
                            </div>
                        </div>

                        {{-- Body: Description & Total --}}
                        <div class="card-body p-3">
                            <p class="mb-2 text-slate-700 fw-medium" style="font-size: 12.5px; line-height: 1.4;">
                                {{ textUpperCase($d->keterangan) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded-3">
                                <span class="text-muted" style="font-size: 11px;">Total Setoran</span>
                                <span class="fw-bold text-primary" style="font-size: 15px;">Rp {{ formatAngka($d->total) }}</span>
                            </div>

                            {{-- Collapsible Breakdown Details --}}
                            <div class="collapse pt-3 mt-2 border-top" id="detail-{{ $d->kode_setoran }}">
                                <div class="row g-2" style="font-size: 11.5px;">
                                    <div class="col-6 d-flex justify-content-between text-muted">
                                        <span>Kertas:</span>
                                        <span class="fw-bold text-slate-800">Rp {{ formatAngka($d->setoran_kertas) }}</span>
                                    </div>
                                    <div class="col-6 d-flex justify-content-between text-muted">
                                        <span>Logam:</span>
                                        <span class="fw-bold text-slate-800">Rp {{ formatAngka($d->setoran_logam) }}</span>
                                    </div>
                                    <div class="col-6 d-flex justify-content-between text-muted">
                                        <span>Transfer:</span>
                                        <span class="fw-bold text-slate-800">Rp {{ formatAngka($d->setoran_transfer) }}</span>
                                    </div>
                                    <div class="col-6 d-flex justify-content-between text-muted">
                                        <span>Giro:</span>
                                        <span class="fw-bold text-slate-800">Rp {{ formatAngka($d->setoran_giro) }}</span>
                                    </div>
                                    
                                    @if ($d->status == '1')
                                        @php
                                            $tgl_terima = $d->tanggal_diterima ?? $d->tanggal_diterima_transfer ?? $d->tanggal_diterima_giro;
                                            $bank_name = !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank;
                                        @endphp
                                        <div class="col-12 mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Diterima di Bank:</span>
                                            <span class="fw-bold text-success">
                                                {{ $bank_name }} ({{ date('d-m-Y', strtotime($tgl_terima)) }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Actions and Toggle --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <button class="btn btn-sm btn-outline-secondary py-1 px-2 d-inline-flex align-items-center gap-1 collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detail-{{ $d->kode_setoran }}" 
                                        aria-expanded="false" 
                                        aria-controls="detail-{{ $d->kode_setoran }}"
                                        style="font-size: 11px; border-radius: 6px;">
                                    <span>Rincian</span>
                                    <i class="ti ti-chevron-down transition-rotate fs-6"></i>
                                </button>
                                
                                <div class="d-flex align-items-center gap-1">
                                    @can('setoranpusat.approve')
                                        @if ($d->status == '0' && ($d->setoran_transfer == 0 || empty($d->setoran_transfer)) && ($d->setoran_giro == 0 || empty($d->setoran_giro)))
                                            <a href="#" class="btn btn-sm btn-success py-1 px-2 btnApprove d-inline-flex align-items-center gap-1"
                                               kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" style="font-size: 11px; border-radius: 6px;">
                                                <i class="ti ti-circle-check fs-6"></i> Approve
                                            </a>
                                        @else
                                            @if (($d->setoran_transfer == 0 || empty($d->setoran_transfer)) && ($d->setoran_giro == 0 || empty($d->setoran_giro)))
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                      action="{{ route('setoranpusat.cancel', Crypt::encrypt($d->kode_setoran)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="btn btn-sm btn-outline-danger py-1 px-2 cancel-confirm d-inline-flex align-items-center gap-1" style="font-size: 11px; border-radius: 6px;">
                                                        <i class="ti ti-square-rounded-x fs-6"></i> Batal
                                                    </a>
                                                </form>
                                            @endif
                                        @endif
                                    @endcan
                                    
                                    @can('setoranpusat.edit')
                                        @if ($d->status == '0' && ($d->setoran_transfer == 0 || empty($d->setoran_transfer)) && ($d->setoran_giro == 0 || empty($d->setoran_giro)) && ($d->no_pengajuan == '' || empty($d->no_pengajuan)))
                                            <a href="#" class="btn btn-sm btn-outline-primary py-1 px-2 btnEdit d-inline-flex align-items-center gap-1"
                                               kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" style="font-size: 11px; border-radius: 6px;">
                                                <i class="ti ti-edit fs-6"></i> Edit
                                            </a>
                                        @endif
                                    @endcan
                                    
                                    @can('setoranpusat.delete')
                                        @if ($d->status == '0' && ($d->setoran_transfer == 0 || empty($d->setoran_transfer)) && ($d->setoran_giro == 0 || empty($d->setoran_giro)) && ($d->no_pengajuan == '' || empty($d->no_pengajuan)))
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                  action="{{ route('setoranpusat.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="btn btn-sm btn-outline-danger py-1 px-2 delete-confirm d-inline-flex align-items-center gap-1" style="font-size: 11px; border-radius: 6px;">
                                                    <i class="ti ti-trash fs-6"></i> Hapus
                                                </a>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Card --}}
            <div class="card border-0 shadow rounded-3 mt-4 mb-5" style="background: linear-gradient(135deg, #002e65 0%, #004b93 100%); color: white; border-radius: 16px !important;">
                <div class="card-body p-3">
                    <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 13px;">
                        <i class="ti ti-calculator fs-5 text-warning"></i>
                        REKAP TOTAL SETORAN
                    </h6>
                    
                    <div class="row g-2 mb-3 text-white-50" style="font-size: 11.5px; border-bottom: 1px dashed rgba(255,255,255,0.2); padding-bottom: 10px;">
                        <div class="col-6 d-flex justify-content-between">
                            <span>Kertas:</span>
                            <span class="text-white fw-bold">Rp {{ formatAngka($setoran_pusat->sum('setoran_kertas')) }}</span>
                        </div>
                        <div class="col-6 d-flex justify-content-between">
                            <span>Logam:</span>
                            <span class="text-white fw-bold">Rp {{ formatAngka($setoran_pusat->sum('setoran_logam')) }}</span>
                        </div>
                        <div class="col-6 d-flex justify-content-between">
                            <span>Transfer:</span>
                            <span class="text-white fw-bold">Rp {{ formatAngka($setoran_pusat->sum('setoran_transfer')) }}</span>
                        </div>
                        <div class="col-6 d-flex justify-content-between">
                            <span>Giro:</span>
                            <span class="text-white fw-bold">Rp {{ formatAngka($setoran_pusat->sum('setoran_giro')) }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white font-medium" style="font-size: 12px; letter-spacing: 0.5px;">GRAND TOTAL:</span>
                        <span class="fw-bold text-warning fs-5">Rp {{ formatAngka($setoran_pusat->sum('total')) }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Floating Action Button (FAB) --}}
@can('setoranpusat.create')
    <button class="fab-btn btn shadow" id="btnCreate" title="Input Setoran">
        <i class="ti ti-plus fs-3"></i>
    </button>
@endcan

{{-- Form Modal --}}
<x-modal-form id="modal" show="loadmodal" title="" />

@endsection

@push('myscript')
<script>
    $(function() {
        // Explicitly initialize flatpickr on page load for mobile date inputs
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
        });

        // Re-initialize or refresh flatpickr when filter is expanded to ensure proper layout rendering
        $('#collapseFilter').on('shown.bs.collapse', function () {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
            });
        });

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }

        $("#btnCreate").click(function(e) {
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Input Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/create`);
        });

        // Event delegation for dynamically and statically bound edit buttons
        $(document).on('click', '.btnEdit', function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/edit`);
        });

        // Event delegation for approve buttons
        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/approve`);
        });

        // Select2 Initialization for Branch Filter
        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
