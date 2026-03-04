@extends('layouts.app')
@section('titlepage', 'Wilayah')

@section('content')
@section('navigasi')
   <span>Wilayah</span>
@endsection
<div class="row">
   <div class="col-lg-6 col-md-12">
      {{-- Filter Section (No Card) --}}
      <form action="{{ route('wilayah.index') }}">
         <div class="row g-2 mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
               <x-input-with-icon label="Cari Wilayah / Rute" value="{{ Request('nama_wilayah') }}"
                  name="nama_wilayah" icon="ti ti-search" hideLabel="true" />
            </div>
            @hasanyrole($roles_show_cabang)
               <div class="col-lg-4 col-md-4 col-sm-12">
                  <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                     textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" hideLabel="true" />
               </div>
            @endhasanyrole
            <div class="col-lg-2 col-md-2 col-sm-12">
               <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
            </div>
         </div>
      </form>

      {{-- Data Card --}}
      <div class="card shadow-sm border mt-2">
         <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
            <div class="d-flex justify-content-between align-items-center">
               <h6 class="m-0 fw-bold text-white"><i class="ti ti-map-pin me-2"></i>Data Wilayah / Rute</h6>
               @can('wilayah.create')
                  <a href="#" class="btn btn-primary btn-sm" id="btncreateWilayah"><i class="ti ti-plus me-1"></i> Tambah</a>
               @endcan
            </div>
         </div>
         <div class="table-responsive text-nowrap">
            <table class="table table-hover">
               <thead class="text-white" style="background-color: #002e65;">
                  <tr>
                     <th class="text-white">No.</th>
                     <th class="text-white">Kode Wilayah</th>
                     <th class="text-white">Nama Wilayah</th>
                     <th class="text-white text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody class="table-border-bottom-0">
                  @foreach ($wilayah as $d)
                     <tr>
                        <td> {{ $loop->iteration + $wilayah->firstItem() - 1 }}</td>
                        <td><span class="fw-semibold">{{ $d->kode_wilayah }}</span></td>
                        <td>{{ $d->nama_wilayah }}</td>
                        <td>
                           <div class="d-flex justify-content-center gap-2">
                              @can('wilayah.edit')
                                 <a href="#" class="editWilayah text-primary" data-bs-toggle="tooltip" title="Edit"
                                    kode_wilayah="{{ Crypt::encrypt($d->kode_wilayah) }}">
                                    <i class="ti ti-pencil"></i>
                                 </a>
                              @endcan
                              @can('wilayah.delete')
                                 <form method="POST" name="deleteform" class="deleteform d-inline"
                                    action="{{ route('wilayah.delete', Crypt::encrypt($d->kode_wilayah)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
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
               {{ $wilayah->links() }}
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="mdlcreateWilayah" size="" show="loadcreateWilayah" title="Tambah Wilayah" />
<x-modal-form id="mdleditWilayah" size="" show="loadeditWilayah" title="Edit Wilayah" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
   $(function() {
      $("#btncreateWilayah").click(function(e) {
         $('#mdlcreateWilayah').modal("show");
         $("#loadcreateWilayah").load('/wilayah/create');
      });

      $(".editWilayah").click(function(e) {
         var kode_wilayah = $(this).attr("kode_wilayah");
         e.preventDefault();
         $('#mdleditWilayah').modal("show");
         $("#loadeditWilayah").load('/wilayah/' + kode_wilayah + '/edit');
      });
   });
</script>
@endpush
