@extends('layouts.app')
@section('titlepage', 'Surat Jalan Angkutan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Surat Jalan Angkutan</h4>
            <small class="text-muted">Mengelola data surat jalan angkutan gudang jadi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Jadi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-truck-delivery me-1"></i>SJ Angkutan</li>
            </ol>
        </nav>
    </div>
@endsection
<style>
    .freeze-1 {
        position: sticky;
        left: 0;
        z-index: 2;
    }

    .freeze-2 {
        position: sticky;
        left: 140px;
        z-index: 2;
    }

    .freeze-3 {
        position: sticky;
        left: 240px;
        z-index: 2;
    }

    .freeze-4 {
        position: sticky;
        left: 440px;
        z-index: 2;
    }

    .freeze-last {
        position: sticky;
        right: 0;
        z-index: 2;
        border-left: 1px solid #dee2e6;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
    }

    /* background color for body cells to avoid transparency */
    tbody td.freeze-1,
    tbody td.freeze-2,
    tbody td.freeze-3,
    tbody td.freeze-4,
    tbody td.freeze-last {
        background-color: #fff !important;
    }

    tbody td {
        white-space: nowrap !important;
    }

    /* background and z-index for headers */
    thead th {
        position: sticky;
        top: 0;
        z-index: 4 !important;
        background-color: #002e65 !important;
        white-space: nowrap !important;
    }

    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-4,
    thead th.freeze-last {
        z-index: 5 !important;
        background-color: #002e65 !important;
    }

    /* Handle striped rows background color for frozen cells */
    .table-striped tbody tr:nth-of-type(odd) td.freeze-1,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-2,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-3,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-4,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-last {
        background-color: #f9f9f9 !important;
    }

    /* Handle hover background color for frozen cells */
    .table-hover tbody tr:hover td.freeze-1,
    .table-hover tbody tr:hover td.freeze-2,
    .table-hover tbody tr:hover td.freeze-3,
    .table-hover tbody tr:hover td.freeze-4,
    .table-hover tbody tr:hover td.freeze-last {
        background-color: #f5f5f5 !important;
    }

    .table-container {
        max-height: 450px;
        overflow-y: auto;
    }
</style>

<div class="row">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('suratjalanangkutan.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="No. Dokumen" name="no_dok_search" icon="ti ti-barcode"
                        value="{{ Request('no_dok_search') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-select label="Angkutan" name="kode_angkutan_search" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan"
                        select2="select2Kodeangkutansearch" upperCase="true" selected="{{ Request('kode_angkutan_search') }}" hideLabel="true" />
                </div>
                <div class="col-lg-1 col-md-12 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck-delivery me-2"></i>Data Surat Jalan Angkutan</h6>
                </div>
            </div>
            <div class="table-container table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white freeze-1" style="min-width: 140px;">NO. DOK</th>
                            <th class="text-white freeze-2" style="min-width: 100px;">TANGGAL</th>
                            <th class="text-white freeze-3" style="min-width: 200px;">TUJUAN</th>
                            <th class="text-white freeze-4" style="min-width: 250px;">ANGKUTAN</th>
                            <th class="text-white" style="min-width: 120px;">NO. POLISI</th>
                            <th class="text-white text-end">TARIF</th>
                            <th class="text-white text-end">TEPUNG</th>
                            <th class="text-white text-end">BS</th>
                            <th class="text-white text-center">KONTRABON</th>
                            <th class="text-white text-center">TGL BAYAR</th>
                            <th class="text-white text-center freeze-last">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($suratjalanangkutan as $d)
                            <tr>
                                <td class="freeze-1"><span class="fw-bold text-primary">{{ $d->no_dok }}</span></td>
                                <td class="freeze-2">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="freeze-3">{{ $d->tujuan }}</td>
                                <td class="freeze-4">{{ $d->nama_angkutan }}</td>
                                <td>{{ $d->no_polisi }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->tarif) }}</td>
                                <td class="text-end">{{ formatAngka($d->tepung) }}</td>
                                <td class="text-end">{{ formatAngka($d->bs) }}</td>
                                <td class="text-center">
                                    @if ($d->tanggal_kontrabon != null)
                                        <span class="badge bg-success">{{ date('d-m-Y', strtotime($d->tanggal_kontrabon)) }}</span>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!empty($d->tanggal_ledger || !empty($d->tanggal_ledger_hutang)))
                                        <span class="badge bg-info">
                                            {{ date('d-m-Y', strtotime($d->tanggal_ledger ?? $d->tanggal_ledger_hutang)) }}
                                        </span>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                </td>
                                <td class="text-center freeze-last">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('suratjalanangkutan.edit')
                                            @if (empty($d->tanggal_kontrabon))
                                                <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                    no_dok="{{ Crypt::encrypt($d->no_dok) }}">
                                                    <i class="ti ti-pencil fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $suratjalanangkutan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@push('myscript')
<script>
    $(function() {
        const select2Kodeangkutansearch = $('.select2Kodeangkutansearch');
        if (select2Kodeangkutansearch.length) {
            select2Kodeangkutansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Angkutan',
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const no_dok = $(this).attr('no_dok');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Angkutan");
            $("#loadmodal").load(`/suratjalanangkutan/${no_dok}/edit`);
        });
    });
</script>
@endpush
