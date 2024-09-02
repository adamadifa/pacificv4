@extends('layouts.app')
@section('titlepage', 'Ajuan Transfer Dana')

@section('content')
@section('navigasi')
    <span>Ajuan Transfer Dana</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('ajuantransfer.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Ajuan Transfer Dana</a>
                    @endcan
                    @can('ajuantransfer.show')
                        <form action="/ajuantransfer/cetak" method="GET" id="formCetak" target="_blank">
                            <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                            <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                            <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                            <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('ajuantransfer.index') }}" id="formSearch">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>

                            @hasanyrole($roles_show_cabang)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endrole

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                            Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Pengajuan</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Bank</th>
                                        <th>No. Rekening</th>
                                        <th>Jumlah</th>
                                        <th style="width: 24%">Keterangan</th>
                                        <th>Validasi</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ajuantransfer as $d)
                                        <tr>
                                            <td style="width: 10%">
                                                @if (!empty($d->bukti))
                                                    <a href="{{ $d->bukti }}" target="_blank"> {{ $d->no_pengajuan }}</a>
                                                @else
                                                    {{ $d->no_pengajuan }}
                                                @endif

                                            </td>
                                            <td style="width: 10%">{{ formatIndo($d->tanggal) }}</td>
                                            <td style="width: 20%">{{ $d->nama }}</td>
                                            <td style="width: 10%">{{ $d->nama_bank }}</td>
                                            <td style="width: 10%">{{ !empty($d->no_rekening) ? $d->no_rekening : '' }}</td>
                                            <td class="text-end fw-bold" style="width: 10%">{{ formatAngka($d->jumlah) }}</td>
                                            <td style="width: 15%">{{ $d->keterangan }}</td>
                                            <td class="text-center">
                                                @if ($d->status == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == '1')
                                                    @if (!empty($d->kode_setoran))
                                                        <span class="badge {{ $d->status_setoran == '1' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ !empty($d->tanggal_proses) ? formatIndo($d->tanggal_proses) : '' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info">Belum di Proses</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Belum di Validasi</span>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('ajuantransfer.edit')
                                                        @if ($d->status === '0')
                                                            <a href="#" class="btnEdit" no_pengajuan="{{ $d->no_pengajuan }}">
                                                                <i class="ti ti-edit text-success me-1"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('ajuantransfer.delete')
                                                        @if ($d->status === '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuantransfer.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('ajuantransfer.approve')
                                                        @if ($d->status === '0')
                                                            <a href="{{ route('ajuantransfer.approve', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                <i class="ti ti-check text-success me-1"></i>
                                                            </a>
                                                        @else
                                                            @if (empty($d->kode_setoran))
                                                                <a href="{{ route('ajuantransfer.cancelapprove', Crypt::encrypt($d->no_pengajuan)) }}"
                                                                    class="btnCancelApprove">
                                                                    <i class="ti ti-circle-x text-danger me-1"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endcan
                                                    @can('ajuantransfer.proses')
                                                        @if ($d->status == '1' && empty($d->kode_setoran))
                                                            <a href="#" class="btnProses" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                <i class="ti ti-external-link text-primary"></i>
                                                            </a>
                                                        @else
                                                            @if ($d->status_setoran == 0 && !empty($d->kode_setoran))
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('ajuantransfer.cancelproses', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm me-1">
                                                                        <i class="ti ti-square-rounded-x text-danger"></i>
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
                        <div style="float: right;">
                            {{ $ajuantransfer->links() }}
                        </div>
                    </div>
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
