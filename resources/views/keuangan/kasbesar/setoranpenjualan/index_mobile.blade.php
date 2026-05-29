@extends('layouts.app')
@section('titlepage', 'Setoran Penjualan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="mb-0 fw-bold text-slate-800">Setoran Penjualan</h4>
            <small class="text-muted" style="font-size: 11px;">Manajemen setoran hasil penjualan per salesman (Mobile)</small>
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
    .setoran-penjualan-card {
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 16px !important;
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .setoran-penjualan-card:active {
        transform: scale(0.99);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }

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
            <div class="collapse {{ Request('dari') || Request('sampai') || Request('kode_cabang_search') || Request('nama_pelanggan_search') || Request('kode_salesman_search') || Request('status') !== null ? 'show' : '' }}" id="collapseFilter">
                <div class="card-body p-3 border-top bg-white">
                    <form action="{{ route('setoranpenjualan.index') }}">
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
                                        select2="select2Kodecabangsearch" hideLabel="true" />
                                </div>
                            @endrole
                            <div class="col-12">
                                <x-input-with-icon label="Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                    name="nama_pelanggan_search" hideLabel="true" placeholder="Nama Pelanggan" />
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-1">
                                    <select name="kode_salesman_search" id="kode_salesman_search"
                                        class="form-select select2Kodesalesmansearch">
                                        <option value="">Semua Salesman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-1">
                                    <select name="status" id="status" class="form-select" style="font-size: 13px;">
                                        <option value="">Status LHP</option>
                                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100 py-2" style="border-radius: 8px;">
                                    <i class="ti ti-search me-1"></i>Terapkan Filter
                                </button>
                            </div>
                            
                            @can('setoranpenjualan.show')
                                <div class="col-12 mt-2 pt-2 border-top d-flex gap-2">
                                    <button form="formCetak" class="btn btn-outline-secondary btn-sm flex-fill" type="submit">
                                        <i class="ti ti-printer me-1"></i>Cetak
                                    </button>
                                    <button form="formCetak" class="btn btn-outline-success btn-sm flex-fill bg-white text-success border-success" name="exportButton" type="submit">
                                        <i class="ti ti-download me-1"></i>Export
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @can('setoranpenjualan.show')
            <form action="/setoranpenjualan/cetak" method="GET" id="formCetak" target="_blank" class="d-none">
                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                <input type="hidden" name="kode_salesman_search" id="kode_salesman_cetak"
                    value="{{ Request('kode_salesman_search') }}" />
            </form>
        @endcan

        {{-- Mobile Data Card List --}}
        @if ($setoran_penjualan->isEmpty())
            {{-- Empty State --}}
            <div class="card border shadow-sm rounded-3 py-5 px-3 text-center my-4">
                <div class="mb-3 text-muted">
                    <i class="ti ti-currency-dollar-off" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-slate-800" style="font-size: 15px;">Tidak Ada Data Setoran Penjualan</h5>
                <p class="text-muted mb-0" style="font-size: 12px;">Silakan tentukan filter pencarian atau tambahkan setoran baru.</p>
            </div>
        @else
            <div class="setoran-penjualan-card-list">
                @foreach ($setoran_penjualan as $key => $d)
                    @php
                        $total_lhp = $d->lhp_tunai + $d->lhp_tagihan;
                        $uk = $d->kurangsetorkertas - $d->lebihsetorkertas;
                        $ul = $d->kurangsetorlogam - $d->lebihsetorlogam;
                        $setoran_kertas = $d->setoran_kertas + $uk;
                        $setoran_logam = $d->setoran_logam + $ul;
                        $total_setoran = $setoran_kertas + $setoran_logam + $d->setoran_giro + $d->setoran_transfer + $d->setoran_lainnya;

                        $cek_tagihan = $d->cek_lhp_tagihan + $d->cek_lhp_giro + $d->cek_lhp_transfer;
                        $color_setoran_tunai = $d->lhp_tunai == $d->cek_lhp_tunai ? 'text-success' : 'text-danger fw-bold';
                        $color_setoran_tagihan = $d->lhp_tagihan == $cek_tagihan ? 'text-success' : 'text-danger fw-bold';
                        $cek_giro_to_cash_transfer = $d->cek_giro_to_cash_transfer;
                        $giro_to_cash_transfer = $d->giro_to_cash + $d->giro_to_transfer;

                        if (
                            $d->lhp_tunai == $d->cek_lhp_tunai &&
                            $d->lhp_tagihan == $cek_tagihan &&
                            $giro_to_cash_transfer == $cek_giro_to_cash_transfer
                        ) {
                            $color_total_lhp = 'text-success';
                            $status_badge = '<span class="badge bg-label-success" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-circle-check-filled me-1 fs-6"></i>Sesuai</span>';
                        } else {
                            $color_total_lhp = 'text-danger fw-bold';
                            $status_badge = '<span class="badge bg-label-danger" style="font-size: 10px; border-radius: 6px;"><i class="ti ti-alert-triangle-filled me-1 fs-6"></i>Selisih</span>';
                        }

                        $selisih = $total_setoran - $total_lhp;
                        $nama_salesman = explode(' ', $d->nama_salesman);
                        $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                    @endphp
                    <div class="card setoran-penjualan-card mb-3 overflow-hidden">
                        {{-- Header: Salesman name & status badge --}}
                        <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background-color: #fcfdfe;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 10px; border-radius: 6px;">{{ $d->kode_cabang }}</span>
                                <span class="text-slate-800 fw-bold" style="font-size: 12.5px;">{{ $nama_depan }}</span>
                            </div>
                            <div>
                                {!! $status_badge !!}
                            </div>
                        </div>

                        {{-- Body: Overview Comparison --}}
                        <div class="card-body p-3">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded text-center showlhp cursor-pointer" tanggal="{{ $d->tanggal }}" kode_salesman="{{ $d->kode_salesman }}">
                                        <span class="text-muted d-block" style="font-size: 10px;">Total LHP <i class="ti ti-external-link" style="font-size: 9px;"></i></span>
                                        <span class="fw-bold {{ $color_total_lhp }}" style="font-size: 13.5px; text-decoration: underline;">
                                            Rp {{ formatAngka($total_lhp) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded text-center">
                                        <span class="text-muted d-block" style="font-size: 10px;">Total Setoran</span>
                                        <span class="fw-bold text-primary" style="font-size: 13.5px;">
                                            Rp {{ formatAngka($total_setoran) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Collapsible breakdown details --}}
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 w-100 d-flex justify-content-between align-items-center mb-3 collapsed" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detail-{{ $d->kode_setoran }}" 
                                    aria-expanded="false">
                                <span style="font-size: 11px;">Rincian LHP & Setoran</span>
                                <i class="ti ti-chevron-down transition-rotate fs-6"></i>
                            </button>

                            <div class="collapse" id="detail-{{ $d->kode_setoran }}">
                                <!-- LHP Breakdown -->
                                <h6 class="fw-bold text-success mb-2" style="font-size: 11px;">Rincian LHP</h6>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background-color: rgba(40, 199, 111, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9.5px; color: #28c76f !important;">Tunai</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11.5px;">Rp {{ formatAngka($d->lhp_tunai) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background-color: rgba(40, 199, 111, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9.5px; color: #28c76f !important;">Tagihan</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11.5px;">Rp {{ formatAngka($d->lhp_tagihan) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Setoran Breakdown -->
                                <h6 class="fw-bold text-danger mb-2" style="font-size: 11px;">Rincian Setoran</h6>
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background-color: rgba(234, 84, 85, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9px; color: #ea5455 !important;">Kertas</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11px;">Rp {{ formatAngka($setoran_kertas) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background-color: rgba(234, 84, 85, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9px; color: #ea5455 !important;">Logam</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11px;">Rp {{ formatAngka($setoran_logam) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background-color: rgba(234, 84, 85, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9px; color: #ea5455 !important;">Lainnya</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11px;">Rp {{ formatAngka($d->setoran_lainnya) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background-color: rgba(67, 138, 243, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9px; color: #438af3 !important;">Giro</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11px;">Rp {{ formatAngka($d->setoran_giro) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background-color: rgba(67, 138, 243, 0.08);">
                                            <span class="text-muted d-block" style="font-size: 9px; color: #438af3 !important;">Transfer</span>
                                            <span class="fw-bold text-slate-800" style="font-size: 11px;">Rp {{ formatAngka($d->setoran_transfer) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selisih details & Keterangan --}}
                                <div class="mt-3 pt-2 border-top text-muted" style="font-size: 11px;">
                                    @if ($selisih != 0)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Selisih (Setoran - LHP):</span>
                                            <span class="fw-bold text-danger">Rp {{ formatAngka($selisih) }}</span>
                                        </div>
                                    @endif
                                    @if ($d->keterangan)
                                        <div class="bg-light p-2 rounded mt-1">
                                            <span class="d-block fw-semibold mb-1" style="font-size: 10px;">Keterangan:</span>
                                            <p class="mb-0 text-slate-700" style="line-height: 1.3;">{{ $d->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Actions and Details --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <span class="text-muted fs-7"><i class="ti ti-calendar me-1"></i>{{ date('d-m-Y', strtotime($d->tanggal)) }}</span>
                                <div class="d-flex align-items-center gap-1">
                                    @can('setoranpenjualan.edit')
                                        <a href="#" class="btn btn-sm btn-icon btn-outline-success btnEdit"
                                           kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" style="border-radius: 8px;">
                                            <i class="ti ti-edit fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('setoranpenjualan.delete')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                              action="{{ route('setoranpenjualan.delete', Crypt::encrypt($d->kode_setoran)) }}">
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
@can('setoranpenjualan.create')
    <a href="#" class="fab-btn" id="btnCreate">
        <i class="ti ti-plus fs-3"></i>
    </a>
@endcan

<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalDetaillhp" show="loadmodaldetaillhp" title="Detail LHP" size="modal-xl" />
@endsection

@push('myscript')
<script>
    $(function() {
        const formCetak = $("#formCetak");

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        function loadingShowlhp() {
            $("#loadmodaldetaillhp").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
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

        getsalesmanbyCabang();

        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Pembayaran Setoran');
            $("#loadmodal").load('/setoranpenjualan/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_setoran = $(this).attr("kode_setoran");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Edit Pembayaran Setoran');
            $("#loadmodal").load(`/setoranpenjualan/${kode_setoran}/edit`);
        });

        $(".showlhp").click(function(e) {
            e.preventDefault();
            loadingShowlhp();
            $("#modalDetaillhp").modal("show");
            const tanggal = $(this).attr("tanggal");
            const kode_salesman = $(this).attr("kode_salesman");
            $.ajax({
                type: 'POST',
                url: '/setoranpenjualan/showlhp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#loadmodaldetaillhp").html(respond);
                }
            });
        });

        $("#formCetak").submit(function(e) {
            var dari = $("#dari_cetak").val();
            var sampai = $("#sampai_cetak").val();

            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Lakukan Pencarian Data Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
@endpush
