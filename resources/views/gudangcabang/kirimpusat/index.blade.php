@extends('layouts.app')
@section('titlepage', 'Kirim Pusat')

@section('content')
@section('navigasi')
   <span>Kirim Pusat</span>
@endsection
<div class="row">
   <div class="col-lg-8 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('kirimpusat.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah Data Kirim Pusat</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row mt-2">
               <div class="col-12">
                  <form action="{{ route('kirimpusat.index') }}" id="formSearch">
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
                              <th>No. Mutasi</th>
                              <th>Tanggal</th>
                              <th>Cabang</th>
                              <th>Keterangan</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($kirimpusat as $d)
                              <tr>
                                 <td>{{ $d->no_mutasi }}</td>
                                 <td>{{ DateToIndo($d->tanggal) }}</td>
                                 <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                 <td>{{ $d->keterangan }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('kirimpusat.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('kirimpusat.show')
                                          <div>
                                             <a href="#" class="me-2 btnShow"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-file-description text-info"></i>
                                             </a>
                                          </div>
                                       @endcan

                                       @can('kirimpusat.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('kirimpusat.delete', Crypt::encrypt($d->no_mutasi)) }}">
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
                     {{ $kirimpusat->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
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
      $("#btnCreate").click(function(e) {
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Kirim Pusat");
         $("#loadmodal").load(`/kirimpusat/create`);
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Detail Kirim Pusat");
         $("#loadmodal").load(`/kirimpusat/${no_mutasi}/show`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Edit Kirim Pusat");
         $("#loadmodal").load(`/kirimpusat/${no_mutasi}/edit`);
      });
   });
</script>
@endpush
