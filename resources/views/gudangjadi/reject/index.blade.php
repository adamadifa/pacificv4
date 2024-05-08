@extends('layouts.app')
@section('titlepage', 'Reject')

@section('content')
@section('navigasi')
   <span>Reject</span>
@endsection
<div class="row">
   <div class="col-lg-7 col-md-12 col-sm-12">
      <div class="nav-align-top nav-tabs-shadow mb-4">
         @include('layouts.navigation_mutasigudangjadi')
         <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
               @can('rejectgudangjadi.create')
                  <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                     Tambah Reject</a>
               @endcan
               <div class="row mt-2">
                  <div class="col-12">
                     <form action="{{ route('rejectgudangjadi.index') }}">
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
                                 <th>No. Reject</th>
                                 <th>Tanggal</th>
                                 <th>#</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($reject as $d)
                                 <tr>
                                    <td>{{ $d->no_mutasi }}</td>
                                    <td>{{ DateToIndo($d->tanggal) }}</td>
                                    <td>
                                       <div class="d-flex">
                                          @can('rejectgudangjadi.edit')
                                             <div>
                                                <a href="#" class="me-2 btnEdit"
                                                   no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                   <i class="ti ti-edit text-success"></i>
                                                </a>
                                             </div>
                                          @endcan
                                          @can('rejectgudangjadi.show')
                                             <div>
                                                <a href="#" class="me-2 btnShow"
                                                   no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                   <i class="ti ti-file-description text-info"></i>
                                                </a>
                                             </div>
                                          @endcan
                                          @can('rejectgudangjadi.delete')
                                             <div>
                                                <form method="POST" name="deleteform" class="deleteform"
                                                   action="{{ route('rejectgudangjadi.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                   @csrf
                                                   @method('DELETE')
                                                   <a href="#" class="delete-confirm me-1">
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
                        {{ $reject->links() }}
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
