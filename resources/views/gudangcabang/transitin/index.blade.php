@extends('layouts.app')
@section('titlepage', 'Transit IN')

@section('content')
@section('navigasi')
   <span>Data Transit IN</span>
@endsection
<div class="row">
   <div class="col-lg-7 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-body">
            <div class="row mt-2">
               <div class="col-12">
                  <form action="{{ route('transitin.index') }}" id="formSearch">
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

                     @hasanyrole($roles_show_cabang)
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12">
                              <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                 key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                 selected="{{ Request('kode_cabang_search') }}"
                                 select2="select2Kodecabangsearch" />
                           </div>
                        </div>
                     @endrole
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
                              <th>Cabang</th>
                              <th>Transit Out</th>
                              <th>Transit IN</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($transit_in as $d)
                              <tr>
                                 <td>{{ $d->no_surat_jalan }}</td>
                                 <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                 <td>{{ DateToIndo($d->tgl_transit_out) }}</td>
                                 <td>
                                    @if (!empty($d->tgl_transit_in))
                                       {{ DateToIndo($d->tgl_transit_in) }}
                                    @else
                                       <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                 </td>
                                 <td class="text-center">
                                    @if (!empty($d->tgl_transit_in))
                                       @can('transitin.delete')
                                          <form method="POST" name="deleteform" class="deleteform"
                                             action="{{ route('transitin.delete', Crypt::encrypt($d->no_surat_jalan)) }}">
                                             @csrf
                                             @method('DELETE')
                                             <a href="#" class="cancel-confirm ml-1">
                                                <i class="ti ti-square-rounded-minus text-warning"></i>
                                             </a>
                                          </form>
                                       @endcan
                                    @else
                                       @can('transitin.create')
                                          <a href="#" class="btnCreate" no_surat_jalan="{{ Crypt::encrypt($d->no_surat_jalan) }}"><i class="ti ti-external-link success"></i></a>
                                       @endcan
                                    @endif
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
                  <div style="float: right;">
                     {{ $transit_in->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
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

      function loading() {
         $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
      };
      $(".btnCreate").click(function(e) {
         e.preventDefault();
         const no_surat_jalan = $(this).attr("no_surat_jalan");
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Approve Transit IN");
         $("#loadmodal").load(`/transitin/${no_surat_jalan}/create`);
      });
   });
</script>
@endpush
