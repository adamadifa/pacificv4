@extends('layouts.app')
@section('titlepage', 'Surat Jalan')

@section('content')
@section('navigasi')
    <span>Surat Jalan</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12">
        @can('suratjalan.approve')
            <div class="alert alert-info alert-dismissible d-flex align-items-baseline" role="alert">
                <span class="alert-icon alert-icon-lg text-info me-2">
                    <i class="ti ti-info-circle ti-sm"></i>
                </span>
                <div class="d-flex flex-column ps-1">
                    <h5 class="alert-heading mb-2">Informasi</h5>
                    <p class="mb-0">
                        Silahkan Gunakan Icon <i class="ti ti-external-link text-primary me-1 ms-1"></i> Untuk Melakukan
                        Penerimaan Data Surat Jalan
                    </p>
                    <p class="mb-0">
                        Silahkan Gunakan Icon <i class="ti ti-square-rounded-minus text-warning me-1 ms-1"></i> Untuk
                        Membatalkan Penerimaan Surat Jalan !
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endcan
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudangjadi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('suratjalan.create')
                        <a href="{{ route('permintaankiriman.index') }}" class="btn btn-primary"><i
                                class="fa fa-plus me-2"></i>
                            Buat Surat Jalan</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('suratjalan.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-input-with-icon label="No. Dokumen" name="no_dok_search" icon="ti ti-barcode"
                                            value="{{ Request('no_dok_search') }}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                            key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                            selected="{{ Request('kode_cabang_search') }}"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <select name="status_search" id="status_search" class="form-select">
                                                <option value="">Semua Status</option>
                                                <option {{ Request('status_search') == '0' ? 'selected' : '' }}
                                                    value="0">
                                                    Belum Diterima Cabang</option>
                                                <option {{ Request('status_search') == '1' ? 'selected' : '' }}
                                                    value="1">Sudah Diterima Cabang</option>
                                                <option {{ Request('status_search') == '2' ? 'selected' : '' }}
                                                    value="2">Transit Out</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                            <th>No. Surat Jalan</th>
                                            <th>No. Dokumen</th>
                                            <th>Tanggal</th>
                                            <th>Cabang</th>
                                            <th>Status</th>
                                            <th>Tanggal Diterima</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($surat_jalan as $d)
                                            <tr>
                                                <td>{{ $d->no_mutasi }}</td>
                                                <td>{{ $d->no_dok }}</td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                                <td>
                                                    @if ($d->status_surat_jalan == 0)
                                                        <span class="badge bg-danger">Belum Diterima Cabang</span>
                                                    @elseif($d->status_surat_jalan == 1)
                                                        <span class="badge bg-success">Sudah Diterima Cabang</span>
                                                    @elseif($d->status_surat_jalan == 2)
                                                        <span class="badge bg-info">Transit Out</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($d->tanggal_mutasi_cabang))
                                                        @if ($d->status_surat_jalan == '1')
                                                            @if (empty($d->tangal_transit_in))
                                                                <span class="badge bg-success">
                                                                    {{ DateToIndo($d->tanggal_mutasi_cabang) }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    {{ DateToIndo($d->tanggal_transit_in) }}
                                                                </span>
                                                            @endif
                                                        @elseif($d->status_surat_jalan == '2')
                                                            <span class="badge bg-info">
                                                                {{ DateToIndo($d->tanggal_mutasi_cabang) }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <i class="ti ti-refresh text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('suratjalan.edit')
                                                            @if ($d->status_surat_jalan == '0')
                                                                <div>
                                                                    <a href="#" class="me-2 btnEdit"
                                                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('suratjalan.show')
                                                            <div>
                                                                <a href="#" class="me-2 btnShow"
                                                                    no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('suratjalan.delete')
                                                            @if ($d->status_surat_jalan == '0')
                                                                <div>
                                                                    <form method="POST" name="deleteform"
                                                                        class="deleteform"
                                                                        action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="delete-confirm me-1">
                                                                            <i class="ti ti-trash text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('suratjalan.approve')
                                                            @if ($d->status_surat_jalan === '0')
                                                                <div>
                                                                    <a href="#"
                                                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                                                                        class="btnApprove">
                                                                        <i class="ti ti-external-link primary "></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <form method="POST" name="deleteform"
                                                                        class="deleteform"
                                                                        action="{{ route('suratjalan.cancel', Crypt::encrypt($d->no_mutasi)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i
                                                                                class="ti ti-square-rounded-minus text-warning"></i>
                                                                        </a>
                                                                    </form>
                                                                </div>
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
                                {{ $surat_jalan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
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
        $(".btnShow").click(function(e) {
            e.preventDefault();
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Surat Jalan");
            $("#loadmodal").load(`/suratjalan/${no_mutasi}/show`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Surat Jalan");
            $("#loadmodal").load(`/suratjalan/${no_mutasi}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Surat Jalan");
            $("#loadmodal").load(`/suratjalan/${no_mutasi}/approveform`);
        });

    });
</script>
@endpush
