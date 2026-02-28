@extends('layouts.app')
@section('titlepage', 'Driver Helper')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Driver Helper</h4>
            <small class="text-muted">Mengelola daftar driver dan helper.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Driver Helper</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('driverhelper.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Cari Nama" value="{{ Request('nama_driver_helper') }}" name="nama_driver_helper"
                        icon="ti ti-search" />
                </div>
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            select2="select2Kodecabangsearch" selected="{{ Request('kode_cabang_search') }}" upperCase="true" />
                    </div>
                @endhasanyrole
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Data Driver Helper</h6>
                    @can('driverhelper.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode</th>
                            <th class="text-white">Nama Driver / Helper</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($driverhelper as $d)
                            <tr>
                                <td> {{ $loop->iteration + $driverhelper->firstItem() - 1 }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_driver_helper }}</span></td>
                                <td>{{ $d->nama_driver_helper }}</td>
                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('driverhelper.edit')
                                            <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_driver_helper="{{ Crypt::encrypt($d->kode_driver_helper) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('driverhelper.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('driverhelper.delete', Crypt::encrypt($d->kode_driver_helper)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
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
                    {{ $driverhelper->links() }}
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
               placeholder: 'Cabang',
               dropdownParent: $this.parent()
            });
         });
      }


      $("#btnCreate").click(function(e) {
         e.preventDefault();
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Driver / Helper");
         $("#loadmodal").load(`/driverhelper/create`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         const kode_driver_helper = $(this).attr('kode_driver_helper');
         $("#modal").modal("show");
         $(".modal-title").text("Edit Data Driver / Helper");
         $("#loadmodal").load(`/driverhelper/${kode_driver_helper}/edit`);
      });
   });
</script>
@endpush
