@extends('layouts.app')
@section('titlepage', 'Setoran Transfer')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="mb-0 fw-bold text-slate-800">Setoran Transfer</h4>
            <small class="text-muted" style="font-size: 11px;">Manajemen setoran dari transfer pelanggan (Mobile View)</small>
        </div>
    </div>
@endsection

<style>
    /* Scrollable Horizontal Sub-navigation */
    .mobile-subnav-container {
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
        margin-bottom: 1rem;
    }
    .mobile-subnav-container::-webkit-scrollbar {
        display: none;
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
    .bg-label-info {
        background-color: rgba(0, 207, 221, 0.1) !important;
        color: #00cfdd !important;
    }
    .bg-label-secondary {
        background-color: rgba(168, 170, 174, 0.1) !important;
        color: #a8aae0 !important;
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

        {{-- Petunjuk Penggunaan Alert --}}
        <div class="alert alert-dismissible fade show p-3 mb-3 border-0 position-relative shadow-sm" role="alert" 
             style="background: linear-gradient(135deg, rgba(0, 46, 101, 0.05) 0%, rgba(0, 75, 147, 0.08) 100%); 
                    border: none !important; 
                    border-radius: 12px;">
            <div class="d-flex align-items-start gap-2">
                <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 32px; height: 32px;">
                    <i class="ti ti-info-circle text-primary fs-4"></i>
                </div>
                <div class="flex-grow-1 ps-1">
                    <h6 class="alert-heading fw-bold text-slate-800 mb-1" style="font-size: 13px;">Petunjuk Penggunaan</h6>
                    <ul class="list-unstyled mb-0 text-slate-600 d-flex flex-column gap-1.5" style="font-size: 11.5px; line-height: 1.4;">
                        <li class="d-flex align-items-center gap-1">
                            <i class="ti ti-circle-chevron-right text-primary" style="font-size: 10px;"></i>
                            <span>Tap <span class="badge bg-label-success px-1.5 py-0.5" style="font-size: 10px; border-radius: 4px;"><i class="ti ti-external-link me-0.5" style="font-size: 11px;"></i>Setorkan</span> untuk input setoran transfer.</span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <i class="ti ti-circle-chevron-right text-primary" style="font-size: 10px;"></i>
                            <span>Tap <span class="badge bg-label-danger px-1.5 py-0.5" style="font-size: 10px; border-radius: 4px;"><i class="ti ti-square-rounded-x me-0.5" style="font-size: 11px;"></i>Batalkan</span> untuk hapus setoran transfer.</span>
                        </li>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 10px; right: 10px; padding: 5px;"></button>
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
            <div class="collapse {{ Request('dari') || Request('sampai') || Request('kode_cabang_search') || Request('nama_pelanggan_search') || Request('kode_salesman_search') || Request('status') !== null ? 'show' : '' }}" id="collapseFilter">
                <div class="card-body p-3 border-top bg-white">
                    <form action="{{ route('setorantransfer.index') }}">
                        <div class="row g-2">
                            <div class="col-6">
                                <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" hideLabel="true" placeholder="Dari" />
                            </div>
                            <div class="col-6">
                                <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                    datepicker="flatpickr-date" hideLabel="true" placeholder="Sampai" />
                            </div>
                            @hasanyrole($roles_show_cabang)
                                <div class="col-12">
                                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                        select2="select2Kodecabangsearch" hideLabel="true" id="kode_cabang_search" />
                                </div>
                            @endrole
                            <div class="col-12">
                                <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                    name="nama_pelanggan_search" hideLabel="true" placeholder="Nama Pelanggan" />
                            </div>
                            <div class="col-12">
                                <select name="kode_salesman_search" id="kode_salesman_search" class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
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
        @if ($transfer->isEmpty())
            {{-- Empty State --}}
            <div class="card border shadow-sm rounded-3 py-5 px-3 text-center my-4">
                <div class="mb-3 text-muted">
                    <i class="ti ti-cash-off" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-slate-800" style="font-size: 15px;">Tidak Ada Data Setoran Transfer</h5>
                <p class="text-muted mb-0" style="font-size: 12px;">Silakan sesuaikan filter tanggal atau pencarian Anda.</p>
            </div>
        @else
            <div class="transfer-card-list">
                @foreach ($transfer as $d)
                    <div class="card setoran-card mb-3 overflow-hidden">
                        {{-- Header --}}
                        <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background-color: #fcfdfe;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 10px; border-radius: 6px;">{{ $d->kode_cabang }}</span>
                                <span class="text-slate-800 fw-bold" style="font-size: 12px; letter-spacing: 0.2px;">{{ $d->kode_transfer }}</span>
                            </div>
                            <div>
                                @if ($d->status == '1')
                                    <span class="badge bg-label-success fw-bold" style="font-size: 10px; border-radius: 6px;" data-bs-toggle="tooltip" title="{{ $d->no_bukti }}">
                                        Diterima ({{ date('d-m-y', strtotime($d->tanggal_diterima)) }})
                                    </span>
                                @elseif($d->status == '2')
                                    <span class="badge bg-label-danger fw-bold" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-x me-1 fs-6"></i>Ditolak</span>
                                @else
                                    <span class="badge bg-label-warning fw-bold" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-hourglass-empty me-1 fs-6"></i>Pending</span>
                                @endif
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 11px;">Pelanggan:</span>
                                <span class="fw-bold text-slate-800 text-end" style="font-size: 12.5px; max-width: 70%;">{{ $d->nama_pelanggan }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 11px;">Salesman:</span>
                                <span class="text-slate-800 text-end" style="font-size: 12px;">{{ $d->nama_salesman }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 11px;">Jatuh Tempo:</span>
                                <span class="fw-bold text-danger" style="font-size: 12px;">{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded-3">
                                <span class="text-muted" style="font-size: 11px;">Jumlah Transfer</span>
                                <span class="fw-bold text-primary" style="font-size: 15px;">Rp {{ formatAngka($d->total) }}</span>
                            </div>

                            {{-- Collapsible Breakdown Details --}}
                            <div class="collapse pt-3 mt-2 border-top" id="detail-transfer-{{ $d->kode_transfer }}">
                                <div class="row g-2" style="font-size: 11.5px;">
                                    <div class="col-12 d-flex justify-content-between text-muted">
                                        <span>Tanggal Input:</span>
                                        <span class="fw-bold text-slate-800">{{ date('d-m-Y', strtotime($d->tanggal)) }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between text-muted">
                                        <span>Bank Pengirim:</span>
                                        <span class="fw-bold text-slate-800">{{ textUpperCase($d->bank_pengirim) }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between text-muted">
                                        <span>Bank Penerima:</span>
                                        <span class="fw-bold text-slate-800">{{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <span>Status Setor:</span>
                                        @if (!empty($d->tanggal_disetorkan))
                                            <span class="badge bg-label-info fw-bold" style="font-size: 10px;">Setor ({{ date('d-m-Y', strtotime($d->tanggal_disetorkan)) }})</span>
                                        @else
                                            <span class="badge bg-label-secondary fw-bold" style="font-size: 10px;">Belum Setor</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <button class="btn btn-sm btn-outline-secondary py-1 px-2 d-inline-flex align-items-center gap-1 collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detail-transfer-{{ $d->kode_transfer }}" 
                                        aria-expanded="false" 
                                        aria-controls="detail-transfer-{{ $d->kode_transfer }}"
                                        style="font-size: 11px; border-radius: 6px;">
                                    <span>Rincian</span>
                                    <i class="ti ti-chevron-down transition-rotate fs-6"></i>
                                </button>
                                
                                <div class="d-flex align-items-center gap-1">
                                    @can('setorantransfer.create')
                                        @if (empty($d->tanggal_disetorkan))
                                            <a href="#" class="btn btn-sm btn-success py-1 px-2 btnCreate d-inline-flex align-items-center gap-1"
                                               kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}" style="font-size: 11px; border-radius: 6px;">
                                                <i class="ti ti-external-link fs-6"></i> Setorkan
                                            </a>
                                        @else
                                            @can('setorantransfer.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                      action="{{ route('setorantransfer.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="btn btn-sm btn-outline-danger py-1 px-2 delete-confirm d-inline-flex align-items-center gap-1" style="font-size: 11px; border-radius: 6px;">
                                                        <i class="ti ti-square-rounded-x fs-6"></i> Batalkan
                                                    </a>
                                                </form>
                                            @endcan
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-3 mb-4 d-flex justify-content-center">
                {{ $transfer->links('vendor.pagination.bootstrap-5') ?? $transfer->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Form Modal --}}
<x-modal-form id="modal" show="loadmodal" title="" />

@endsection

@push('myscript')
<script>
    $(function() {
        // Explicitly initialize flatpickr for mobile date fields
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
        });

        // Re-initialize flatpickr when collapsible filter opens
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

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        // Modal triggers with event delegation
        $(document).on('click', '.btnCreate', function(e) {
            e.preventDefault();
            loading();
            const kode_transfer = $(this).attr("kode_transfer");
            $('#modal').modal("show");
            $("#loadmodal").load(`/setorantransfer/${kode_transfer}/create`);
            $("#modal").find(".modal-title").text("Setoran Transfer");
        });
    });
</script>
@endpush
