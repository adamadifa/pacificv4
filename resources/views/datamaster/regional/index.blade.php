@extends('layouts.app')
@section('titlepage', 'Regional')

@section('content')
@section('navigasi')
   <span>Regional</span>
@endsection
<div class="row">
   <div class="col-lg-6 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('regional.create')
               <a href="#" class="btn btn-primary" id="btncreateRegional"><i class="fa fa-plus me-2"></i> Tambah
                  Regional</a>
            @endcan

         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('regional.index') }}">
                     <div class="row">
                        <div class="col-lg-10 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cara Regional" value="{{ Request('nama_regional') }}"
                              name="nama_regional" icon="ti ti-search" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary">Cari</button>
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
                              <th>Kode Regional</th>
                              <th>Nama Regional</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($regional as $d)
                              <tr>
                                 <td> {{ $loop->iteration }}</td>
                                 <td>{{ $d->kode_regional }}</td>
                                 <td>{{ $d->nama_regional }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('regional.edit')
                                          <div>
                                             <a href="#" class="me-2 editRegional"
                                                kode_regional="{{ Crypt::encrypt($d->kode_regional) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('regional.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('regional.delete', Crypt::encrypt($d->kode_regional)) }}">
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
                     {{-- {{ $Regionals->links() }} --}}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="mdlcreateRegional" size="" show="loadcreateRegional" title="Tambah Regional" />
<x-modal-form id="mdleditRegional" size="" show="loadeditRegional" title="Edit Regional" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
   $(function() {
      $("#btncreateRegional").click(function(e) {
         $('#mdlcreateRegional').modal("show");
         $("#loadcreateRegional").load('/regional/create');
      });

      $(".editRegional").click(function(e) {
         var kode_regional = $(this).attr("kode_regional");
         e.preventDefault();
         $('#mdleditRegional').modal("show");
         $("#loadeditRegional").load('/regional/' + kode_regional + '/edit');
      });
   });
</script>
@endpush
