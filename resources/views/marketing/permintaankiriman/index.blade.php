@extends('layouts.app')
@section('titlepage', 'Permintaan Kiriman')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Permintaan Kiriman</h4>
            <small class="text-muted">Kelola data permintaan kiriman barang ke cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package me-1"></i>Permintaan Kiriman</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.7rem !important;
    }

    .table th,
    .table td {
        padding: 0.5rem 0.5rem !important;
    }

    .table {
        font-size: 13px !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="alert alert-info alert-dismissible d-flex align-items-baseline shadow-sm" role="alert">
            <span class="alert-icon alert-icon-lg text-info me-2">
                <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Informasi</h5>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-external-link text-primary me-1 ms-1"></i> Untuk membuat Surat Jalan !
                </p>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-square-rounded-minus text-warning me-1 ms-1"></i> Untuk Membatalkan Surat Jalan !
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('permintaankiriman.index') }}" id="formSearch">
            <div class="row g-2 mb-2">
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                        textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                        select2="select2Kodecabangsearch" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="status_search" id="status_search" class="form-select select2Statussearch">
                            <option value="">Semua Status</option>
                            <option {{ Request('status_search') == '0|pk' ? 'selected' : '' }} value="0|pk">Belum Di Proses</option>
                            <option {{ Request('status_search') == '1|pk' ? 'selected' : '' }} value="1|pk">Sudah Di Proses Gudang</option>
                            <option {{ Request('status_search') == '0|sj' ? 'selected' : '' }} value="0|sj">Belum Diterima Cabang</option>
                            <option {{ Request('status_search') == '1|sj' ? 'selected' : '' }} value="1|sj">Sudah Diterima Cabang</option>
                            <option {{ Request('status_search') == '2|sj' ? 'selected' : '' }} value="2|sj">Transit Out</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-auto col-md-2 col-sm-12 d-flex align-items-end">
                    <div class="form-group mb-1">
                        <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package me-2"></i>Data Permintaan Kiriman</h6>
                    @can('permintaankiriman.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat Permintaan</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white">No. Permintaan</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Keterangan</th>
                            <th class="text-white">Status PK</th>
                            <th class="text-white">Salesman</th>
                            <th class="text-white">Status SJ</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($pk as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_permintaan }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>{{ textUpperCase($d->kode_cabang) }}</td>
                                <td>{{ $d->keterangan }}</td>
                                 <td>
                                     @if ($d->status == 1)
                                         <span class="badge bg-success shadow-sm">{{ $d->no_mutasi }}</span>
                                     @else
                                         <i class="ti ti-refresh text-warning ti-spin"></i>
                                     @endif
                                 </td>
                                <td>
                                    @php
                                        $nama_sales = explode(' ', $d->nama_salesman);
                                    @endphp
                                    {{ $nama_sales[0] }}
                                </td>
                                <td>
                                    @if ($d->status == 1)
                                        @if ($d->status_surat_jalan == 0)
                                            <span class="badge bg-danger shadow-sm">Belum Diterima</span>
                                        @elseif($d->status_surat_jalan == 1)
                                            <span class="badge bg-success shadow-sm">Sudah Diterima</span>
                                        @elseif($d->status_surat_jalan == 2)
                                            <span class="badge bg-info shadow-sm">Transit Out</span>
                                        @endif
                                    @else
                                        <i class="ti ti-refresh text-warning ti-spin"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('permintaankiriman.edit')
                                            @if ($d->status == 0)
                                                <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                                    no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('permintaankiriman.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('permintaankiriman.delete')
                                            @if ($d->status == 0)
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('permintaankiriman.delete', Crypt::encrypt($d->no_permintaan)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('suratjalan.create')
                                            @if ($d->status === '0')
                                                <a href="#" class="btnCreateSuratjalan text-primary" data-bs-toggle="tooltip"
                                                    title="Buat Surat Jalan" no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                    <i class="ti ti-external-link fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('suratjalan.delete')
                                            @if ($d->status == '1' && $d->status_surat_jalan === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-warning p-0"
                                                        data-bs-toggle="tooltip" title="Batalkan Surat Jalan">
                                                        <i class="ti ti-square-rounded-minus fs-5"></i>
                                                    </button>
                                                </form>
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
                    {{ $pk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" show="loadCreate" title="Buat Permintaan" />
<x-modal-form id="mdlEdit" show="loadEdit" title="Edit Permintaan" />
<x-modal-form id="mdlDetail" show="loadDetail" title="Detail Permintaan" />
<x-modal-form id="mdlCreateSuratjalan" show="loadCreateSuratjalan" title="Buat Surat Jalan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
   $(function() {
      function loadingElement() {
         const loading = `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`;

         return loading;
      };



      $("#btnCreate").click(function(e) {
         e.preventDefault();
         $('#mdlCreate').modal("show");
         $("#loadEdit").html('');
         $("#loadCreateSuratjalan").html('');
         $("#loadCreate").html(loadingElement());
         $("#loadCreate").load("{{ route('permintaankiriman.create') }}");
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         const no_permintaan = $(this).attr('no_permintaan');
         $('#mdlDetail').modal("show");
         $("#loadDetail").html(loadingElement());
         $("#loadDetail").load(`/permintaankiriman/${no_permintaan}/show`);
      });

      $(".btnEdit").click(function(e) {
         const no_permintaan = $(this).attr("no_permintaan");
         e.preventDefault();
         $("#loadCreate").html('');
         $("#loadCreateSuratjalan").html('');
         $('#mdlEdit').modal("show");
         $("#loadEdit").html(loadingElement());
         $("#loadEdit").load(`/permintaankiriman/${no_permintaan}/edit`);
      });

      $(".btnCreateSuratjalan").click(function(e) {
         e.preventDefault();
         const no_permintaan = $(this).attr("no_permintaan");
         $("#loadCreate").html('');
         $("#loadEdit").html('');
         $('#mdlCreateSuratjalan').modal("show");
         $("#loadCreateSuratjalan").html(loadingElement());
         $("#loadCreateSuratjalan").load(`/suratjalan/${no_permintaan}/create`);
      });
   });
</script>
@endpush
