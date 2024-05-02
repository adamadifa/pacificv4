@extends('layouts.app')
@section('titlepage', 'Driver Helper')

@section('content')
@section('navigasi')
   <span>Driver Helper</span>
@endsection
<div class="row">
   <div class="col-lg-6 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('driverhelper.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah Driver Helper</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('driverhelper.index') }}">
                     <div class="row">
                        <div class="col-lg-6 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cari Driver Helper" value="{{ Request('nama_driver_helper') }}"
                              name="nama_driver_helper" icon="ti ti-search" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                           <div class="col-lg-4 col-sm-12 col-md-12">
                              <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                 textShow="nama_cabang" select2="select2Kodecabangsearch" selected="{{ Request('kode_cabang_search') }}" upperCase="true" />
                           </div>
                        @endhasanyrole
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1"></i>Cari</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="table-responsive mb-2">
                     <table class="table">
                        <thead class="table-dark">
                           <tr>
                              <th>No.</th>
                              <th>Kode</th>
                              <th>Nama Driver / Helper</th>
                              <th>Cabang</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($driverhelper as $d)
                              <tr>
                                 <td> {{ $loop->iteration }}</td>
                                 <td>{{ $d->kode_driver_helper }}</td>
                                 <td>{{ $d->nama_driver_helper }}</td>
                                 <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('driverhelper.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                kode_driver_helper="{{ Crypt::encrypt($d->kode_driver_helper) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('driverhelper.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('driverhelper.delete', Crypt::encrypt($d->kode_driver_helper)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm ml-1">
                                                   <i class="ti ti-trash text-danger"></i>
                                                </a>
                                             </form>
                                          </div>
                                       @endcan

                                    </div>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
                  <div style="float: right;">
                     {{ $driverhelper->links() }}
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
