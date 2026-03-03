@extends('layouts.app')
@section('titlepage', 'Setoran Pusat')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Setoran Pusat</h4>
            <small class="text-muted">Manajemen setoran kas ke pusat.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-building-bank me-1"></i>Setoran Pusat</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .sticky-column {
        position: sticky !important;
        z-index: 1;
    }

    .sticky-left-1 {
        left: 0;
        background-color: inherit !important;
    }

    .sticky-left-2 {
        left: 100px;
        /* Adjusted based on Tanggal width */
        background-color: inherit !important;
    }

    .sticky-right-1 {
        right: 0;
        background-color: inherit !important;
    }

    th.sticky-column {
        z-index: 3 !important;
        background-color: #002e65 !important;
    }

    tr:nth-child(even) td.sticky-column {
        background-color: #f2f2f2 !important;
    }

    tr:nth-child(odd) td.sticky-column {
        background-color: #ffffff !important;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        {{-- Navigation --}}
        <div class="mb-3">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Filter Section --}}
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="card-body p-0">
                <form action="{{ route('setoranpusat.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-7 col-md-9">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                        <div class="col-lg-1 col-md-3">
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table Section --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Setoran Pusat</h6>
                @can('setoranpusat.create')
                    <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                        <i class="ti ti-plus me-1"></i>Input Setoran
                    </a>
                @endcan
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped text-center align-middle" style="font-size: 13px !important;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-white align-middle sticky-column sticky-left-1"
                                style="background-color: #002e65 !important; min-width: 100px; width: 100px;">TANGGAL</th>
                            <th rowspan="2" class="text-white align-middle sticky-column sticky-left-2"
                                style="background-color: #002e65 !important; width: 350px; min-width: 350px;">KETERANGAN</th>
                            <th colspan="4" class="text-white text-center" style="background-color: #002e65 !important;">RINCIAN SETORAN</th>
                            <th rowspan="2" class="text-white align-middle" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th rowspan="2" class="text-white align-middle" style="background-color: #002e65 !important;">BANK</th>
                            <th rowspan="2" class="text-white align-middle" style="background-color: #002e65 !important;">STATUS</th>
                            <th rowspan="2" class="text-white align-middle sticky-column sticky-right-1"
                                style="background-color: #002e65 !important; width: 80px; min-width: 80px;">#</th>
                        </tr>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KERTAS</th>
                            <th class="text-white" style="background-color: #002e65 !important;">LOGAM</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TRANSFER</th>
                            <th class="text-white" style="background-color: #002e65 !important;">GIRO</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($setoran_pusat as $d)
                            <tr>
                                <td class="px-2 sticky-column sticky-left-1">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="text-start px-2 sticky-column sticky-left-2" style="white-space: normal !important;">
                                    {{ textUpperCase($d->keterangan) }}</td>
                                <td class="text-end">{{ formatAngka($d->setoran_kertas) }}</td>
                                <td class="text-end">{{ formatAngka($d->setoran_logam) }}</td>
                                <td class="text-end">{{ formatAngka($d->setoran_transfer) }}</td>
                                <td class="text-end">{{ formatAngka($d->setoran_giro) }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatAngka($d->total) }}</td>
                                <td>
                                    @if ($d->status == '1')
                                        <span class="fw-bold">{{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->status == '1')
                                        @php
                                            $tgl_terima = $d->tanggal_diterima ?? $d->tanggal_diterima_transfer ?? $d->tanggal_diterima_giro;
                                        @endphp
                                        <span class="badge bg-success">{{ date('d-m-Y', strtotime($tgl_terima)) }}</span>
                                    @elseif($d->status == '2')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning"><i class="ti ti-hourglass-empty"></i></span>
                                    @endif
                                </td>
                                <td class="sticky-column sticky-right-1">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('setoranpusat.approve')
                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro))
                                                <a href="#" class="btnApprove text-primary"
                                                    kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" data-bs-toggle="tooltip" title="Approve">
                                                    <i class="ti ti-external-link fs-4"></i>
                                                </a>
                                            @else
                                                @if (empty($d->setoran_transfer) && empty($d->setoran_giro))
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('setoranpusat.cancel', Crypt::encrypt($d->kode_setoran)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip" title="Batalkan Approval">
                                                            <i class="ti ti-square-rounded-x fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('setoranpusat.edit')
                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                                <a href="#" class="btnEdit text-success"
                                                    kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit fs-4"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('setoranpusat.delete')
                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('setoranpusat.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-4"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f8f9fa;">
                            <th colspan="2" class="text-center sticky-column sticky-left-1" style="background-color: #f8f9fa !important;">TOTAL</th>
                            <th class="text-end">{{ formatAngka($setoran_pusat->sum('setoran_kertas')) }}</th>
                            <th class="text-end">{{ formatAngka($setoran_pusat->sum('setoran_logam')) }}</th>
                            <th class="text-end">{{ formatAngka($setoran_pusat->sum('setoran_transfer')) }}</th>
                            <th class="text-end">{{ formatAngka($setoran_pusat->sum('setoran_giro')) }}</th>
                            <th class="text-end fw-bold text-primary">{{ formatAngka($setoran_pusat->sum('total')) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        $("#btnCreate").click(function(e) {
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Input Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/edit`);
        });


        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/approve`);
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
