@extends('layouts.app')
@section('titlepage', 'Reject')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Reject</h4>
            <small class="text-muted">Mengelola data reject gudang jadi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Jadi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-trash-x me-1"></i>Reject</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudangjadi')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('rejectgudangjadi.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-trash-x me-2"></i>Data Reject</h6>
                    @can('rejectgudangjadi.create')
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
                            <th class="text-white" style="background-color: #002e65 !important;">NO. REJECT</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($reject as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_mutasi }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('rejectgudangjadi.edit')
                                            <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('rejectgudangjadi.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('rejectgudangjadi.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('rejectgudangjadi.delete', Crypt::encrypt($d->no_mutasi)) }}">
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
                    {{ $reject->links() }}
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
         $(".modal-title").text("Tambah Data Reject");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/rejectgudangjadi/create`);
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         $("#modal").modal("show");
         $(".modal-title").text("Detail Reject");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/rejectgudangjadi/${no_mutasi}/show`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         $("#modal").modal("show");
         $(".modal-title").text("Edit Reject");
         $("#loadmodal").html(loadingElement());
         $("#loadmodal").load(`/rejectgudangjadi/${no_mutasi}/edit`);
      });
   });
</script>
@endpush
