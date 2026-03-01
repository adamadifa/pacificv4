@extends('layouts.app')
@section('titlepage', 'Ajuan Transfer Dana')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ajuan Transfer Dana</h4>
            <small class="text-muted">Manajemen pengajuan transfer dana.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-send me-1"></i>Ajuan Transfer Dana</li>
            </ol>
        </nav>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        {{-- Filter Section --}}
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="card-body p-0">
                <form action="{{ route('ajuantransfer.index') }}" id="formSearch">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-4">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-6 col-md-4">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                        <div class="col-lg-2 col-md-12">
                            <div class="form-group mb-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-search"></i>
                                </button>
                                @can('ajuantransfer.show')
                                    <button form="formCetak" class="btn btn-outline-primary" type="submit" name="cetakButton"
                                        data-bs-toggle="tooltip" title="Cetak">
                                        <i class="ti ti-printer"></i>
                                    </button>
                                    <button form="formCetak" class="btn btn-outline-success" type="submit" name="exportButton"
                                        data-bs-toggle="tooltip" title="Excel">
                                        <i class="ti ti-download"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
                @can('ajuantransfer.show')
                    <form action="/ajuantransfer/cetak" method="GET" id="formCetak" target="_blank">
                        <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                        <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                        <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak"
                            value="{{ Request('kode_cabang_search') }}" />
                    </form>
                @endcan
            </div>
        </div>

        {{-- Data Table Section --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Ajuan Transfer Dana</h6>
                @can('ajuantransfer.create')
                    <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                        <i class="ti ti-plus me-1"></i>Buat Ajuan
                    </a>
                @endcan
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle" style="font-size: 13px !important;">
                    <thead>
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. PENGAJUAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NAMA</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">BANK</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. REKENING</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 20%;">KETERANGAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">VALIDASI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($ajuantransfer as $d)
                            <tr>
                                <td class="text-center">
                                    @if (!empty($d->bukti))
                                        <a href="{{ $d->bukti }}" target="_blank" class="fw-bold"> {{ $d->no_pengajuan }}</a>
                                    @else
                                        <span class="fw-bold text-primary">{{ $d->no_pengajuan }}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>{{ textUpperCase($d->nama) }}</td>
                                <td class="text-center">{{ $d->nama_bank }}</td>
                                <td class="text-center">{{ $d->no_rekening }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                <td style="white-space: normal !important;">{{ $d->keterangan }}</td>
                                <td class="text-center">
                                    @if ($d->status == '1')
                                        <span class="badge bg-label-success"><i class="ti ti-checks me-1"></i>Valid</span>
                                    @else
                                        <span class="badge bg-label-warning"><i class="ti ti-hourglass-low me-1"></i>Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->status == '1')
                                        @if (!empty($d->kode_setoran))
                                            <span class="badge {{ $d->status_setoran == '1' ? 'bg-success' : 'bg-warning' }}">
                                                <i class="ti ti-circle-check me-1"></i>
                                                {{ !empty($d->tanggal_proses) ? date('d-m-Y', strtotime($d->tanggal_proses)) : 'Proses' }}
                                            </span>
                                        @else
                                            <span class="badge bg-label-info">Belum diproses</span>
                                        @endif
                                    @else
                                        <span class="badge bg-label-danger">Belum divalidasi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('ajuantransfer.edit')
                                            @if ($d->status === '0')
                                                <a href="#" class="btnEdit text-success" no_pengajuan="{{ $d->no_pengajuan }}"
                                                    data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit fs-4"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('ajuantransfer.delete')
                                            @if ($d->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('ajuantransfer.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-4"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('ajuantransfer.approve')
                                            @if ($d->status === '0')
                                                <a href="{{ route('ajuantransfer.approve', Crypt::encrypt($d->no_pengajuan)) }}"
                                                    class="text-primary" data-bs-toggle="tooltip" title="Validasi">
                                                    <i class="ti ti-circle-check fs-4"></i>
                                                </a>
                                            @else
                                                @if (empty($d->kode_setoran))
                                                    <a href="{{ route('ajuantransfer.cancelapprove', Crypt::encrypt($d->no_pengajuan)) }}"
                                                        class="text-warning" data-bs-toggle="tooltip" title="Batalkan Validasi">
                                                        <i class="ti ti-circle-x fs-4"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endcan

                                        @can('ajuantransfer.proses')
                                            @if ($d->status == '1' && empty($d->kode_setoran))
                                                <a href="#" class="btnProses text-info"
                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip"
                                                    title="Proses Transfer">
                                                    <i class="ti ti-external-link fs-4"></i>
                                                </a>
                                            @else
                                                @if ($d->status_setoran == 0 && !empty($d->kode_setoran))
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('ajuantransfer.cancelproses', Crypt::encrypt($d->no_pengajuan)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Batalkan Proses">
                                                            <i class="ti ti-square-x fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer px-3 py-2 border-top">
                <div class="d-flex justify-content-end">
                    {{ $ajuantransfer->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        const form = $("#formSearch");
        const formCetak = $("#formCetak");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
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
            $(".modal-title").text("Buat Ajuan Transfer Dana");
            $("#loadmodal").load(`/ajuantransfer/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            var no_pengajuan = $(this).attr("no_pengajuan");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Ajuan Transfer Dana");
            $("#loadmodal").load(`/ajuantransfer/${no_pengajuan}/edit`);
        });

        $(".btnProses").click(function(e) {
            e.preventDefault();
            loading();
            var no_pengajuan = $(this).attr("no_pengajuan");
            $("#modal").modal("show");
            $(".modal-title").text("Proses Ajuan Transfer Dana");
            $("#loadmodal").load(`/ajuantransfer/${no_pengajuan}/proses`);
        });
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

        formCetak.submit(function(e) {
            const dari = $("#dari_cetak").val();
            const sampai = $("#sampai_cetak").val();
            const kode_cabang = $("#kode_cabang_cetak").val();
            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Lakukan Pencarian Data Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
