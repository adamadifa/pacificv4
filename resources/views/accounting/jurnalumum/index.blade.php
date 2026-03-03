@extends('layouts.app')
@section('titlepage', 'Jurnal Umum')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jurnal Umum</h4>
            <small class="text-muted">Manajemen posting jurnal umum (Accounting).</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-book me-1"></i>Jurnal Umum</li>
            </ol>
        </nav>
    </div>
@endsection
<style>
    .col-keterangan {
        width: 25% !important;
        white-space: normal !important;
        min-width: 200px !important;
    }

    .col-akun {
        width: 20% !important;
        white-space: normal !important;
        min-width: 150px !important;
    }

    .table {
        font-size: 14px !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('jurnalumum.index') }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
            </div>
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-9 col-sm-12">
                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        upperCase="true" selected="{{ Request('kode_cabang_search') }}" select2="select2Kodecabangsearch" />
                </div>
                <div class="col-auto">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Data Jurnal Umum</h6>
                    @can('jurnalumum.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Jurnal Umum</a>
                    @endcan
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white py-3">KODE JU</th>
                            <th class="text-white py-3">TANGGAL</th>
                             <th class="text-white py-3 col-keterangan">KETERANGAN</th>
                             <th class="text-white py-3 col-akun">AKUN</th>
                            <th class="text-white py-3">PERUNTUKAN</th>
                            <th class="text-white py-3 text-end">DEBET</th>
                            <th class="text-white py-3 text-end">KREDIT</th>
                            <th class="text-white py-3">DEPT</th>
                            <th class="text-white text-center py-3" style="width: 80px;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($jurnalumum as $d)
                            @php
                                $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                $color_cr = !empty($d->kode_cr) ? 'table-primary text-primary' : '';
                            @endphp
                            <tr class="{{ $color_cr }}">
                                <td class="py-2"><span class="fw-bold">{{ $d->kode_ju }}</span></td>
                                <td class="py-2">{{ formatIndo($d->tanggal) }}</td>
                                <td class="py-2 col-keterangan">{{ $d->keterangan }}</td>
                                <td class="py-2 col-akun"><span class="badge bg-label-primary">{{ $d->kode_akun }}</span> {{ $d->nama_akun }}</td>
                                <td class="py-2">{{ $d->kode_peruntukan }} {{ !empty($d->kode_cabang) ? '(' . $d->kode_cabang . ')' : '' }}</td>
                                <td class="py-2 text-end fw-semibold">{{ formatAngkaDesimal($debet) }}</td>
                                <td class="py-2 text-end fw-semibold">{{ formatAngkaDesimal($kredit) }}</td>
                                <td class="py-2">{{ $d->kode_dept }}</td>
                                <td class="py-2">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('jurnalumum.edit')
                                            <a href="#" class="btnEdit text-success" kode_ju="{{ Crypt::encrypt($d->kode_ju) }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('jurnalumum.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('jurnalumum.delete', Crypt::encrypt($d->kode_ju)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="ti ti-database-off d-block mb-1 fs-2"></i>
                                    Tidak ada data ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
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
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Input Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/create`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_ju = $(this).attr('kode_ju');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/${kode_ju}/edit`);
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });
    });
</script>
@endpush
