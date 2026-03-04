@extends('layouts.app')
@section('titlepage', 'Barang Masuk Gudang Bahan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Barang Masuk Gudang Bahan</h4>
            <small class="text-muted">Mengelola data barang masuk ke gudang bahan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Bahan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-import me-1"></i>Barang Masuk</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudangbahan')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('barangmasukgudangbahan.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" hideLabel="true" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" hideLabel="true" />
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                value="{{ Request('no_bukti_search') }}" hideLabel="true" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_asal_barang_search" id="kode_asal_barang_search" class="form-select">
                                    <option value="">Asal Barang</option>
                                    @foreach ($list_asal_barang as $d)
                                        <option value="{{ $d['kode_asal_barang'] }}"
                                            {{ Request('kode_asal_barang_search') == $d['kode_asal_barang'] ? 'selected' : '' }}>
                                            {{ $d['asal_barang'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-import me-2"></i>Data Barang Masuk</h6>
                    @can('barangmasukgb.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Data
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. BUKTI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">ASAL BARANG</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barangmasuk as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_bukti }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>{{ $asal_barang[$d->kode_asal_barang] }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('barangmasukgb.edit')
                                            <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('barangmasukgb.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('barangmasukgb.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('barangmasukgudangbahan.delete', Crypt::encrypt($d->no_bukti)) }}">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $barangmasuk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
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
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Barang Masuk");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/barangmasukgudangbahan/create`);
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         var no_bukti = $(this).attr("no_bukti");
         e.preventDefault();
         $("#modal").modal("show");
         $(".modal-title").text("Detail Barang Masuk");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/barangmasukgudangbahan/${no_bukti}/show`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         var no_bukti = $(this).attr("no_bukti");
         e.preventDefault();
         $("#modal").modal("show");
         $(".modal-title").text("Edit Barang Masuk");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/barangmasukgudangbahan/${no_bukti}/edit`);
      });
   });
</script>
@endpush
