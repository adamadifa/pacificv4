@extends('layouts.app')
@section('titlepage', 'Tujuan Angkutan')

@section('content')
@section('navigasi')
   <span>Tujuan Angkutan</span>
@endsection
<div class="row">
   <div class="col-lg-6 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('tujuanangkutan.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                  Data</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('tujuanangkutan.index') }}">
                     <div class="row">
                        <div class="col-lg-10 col-sm-12 col-md-12">
                           <x-input-with-icon icon="ti ti-search" label="Cari Nama Tujuan" name="tujuan_search"
                              value="{{ Request('tujuan_search') }}" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1 w-100"></i>Cari</button>
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
                              <th>Tujuan</th>
                              <th>Tarif</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($tujuanangkutan as $d)
                              <tr>
                                 <td>{{ $loop->iteration + $tujuanangkutan->firstItem() - 1 }}</td>
                                 <td>{{ $d->kode_tujuan }}</td>
                                 <td>{{ $d->tujuan }}</td>
                                 <td class="text-end">{{ formatAngka($d->tarif) }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('tujuanangkutan.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                kode_tujuan="{{ Crypt::encrypt($d->kode_tujuan) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan

                                       @can('tujuanangkutan.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('tujuanangkutan.delete', Crypt::encrypt($d->kode_tujuan)) }}">
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
                     {{ $tujuanangkutan->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Tambah Tujuan Angkutan" />
<x-modal-form id="mdlEdit" size="" show="loadEdit" title="Edit Tujuan Angkutan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
   $(function() {
      $("#btnCreate").click(function(e) {
         $('#mdlCreate').modal("show");
         $("#loadCreate").load('/tujuanangkutan/create');
      });

      $(".btnEdit").click(function(e) {
         var kode_tujuan = $(this).attr("kode_tujuan");
         e.preventDefault();
         $('#mdlEdit').modal("show");
         $("#loadEdit").load('/tujuanangkutan/' + kode_tujuan + '/edit');
      });
   });
</script>
@endpush
