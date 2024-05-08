@extends('layouts.app')
@section('titlepage', 'OMAN (Order Management)')

@section('content')
@section('navigasi')
   <span>OMAN (Order Management)</span>
@endsection
{{-- <style>
   .modal:nth-of-type(even) {
      z-index: 1052 !important;
   }

   .modal-backdrop.show:nth-of-type(even) {
      z-index: 1051 !important;
   }
</style> --}}
<div class="row">
   <div class="col-lg-6">
      <div class="nav-align-top nav-tabs-shadow mb-4">
         @include('layouts.navigation_oman')
         <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
               @can('oman.create')
                  <a href="#" id="createOman" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                     Buat Oman</a>
               @endcan
               <div class="row mt-2">
                  <div class="col-12">
                     <form action="{{ route('oman.index') }}">
                        <div class="row">
                           <div class="col-lg-6 col-sm-12 col-md-12">
                              <div class="form-group mb-3">
                                 <select name="bulan_search" id="bulan_search" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                       <option
                                          {{ Request('bulan_search') == $d['kode_bulan'] ? 'selected' : '' }}
                                          value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-lg-4 col-sm-12 col-md-12">
                              <div class="form-group mb-3">
                                 <select name="tahun_search" id="tahun_search" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                       <option
                                          @if (!empty(Request('tahun_search'))) {{ Request('tahun_search') == $t ? 'selected' : '' }}
                                                    @else
                                                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                                          value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                 </select>
                              </div>
                           </div>
                           <div class="col-lg-2 col-sm-12 col-md-12">
                              <button class="btn btn-primary"><i
                                    class="ti ti-icons ti-search me-1"></i></button>
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
                                 <th>Kode</th>
                                 <th>Bulan</th>
                                 <th>Tahun</th>
                                 <th>Status</th>
                                 <th>#</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($oman as $d)
                                 <tr>
                                    <td>{{ $d->kode_oman }}</td>
                                    <td>{{ $namabulan[$d->bulan] }}</td>
                                    <td>{{ $d->tahun }}</td>
                                    <td>
                                       @if ($d->status_oman === '1')
                                          <span class="badge bg-primary">Sudah di Proses Gudang</span>
                                       @elseif ($d->status_oman === '2')
                                          <span class="badge bg-success">Sudah di Proses Produksi</span>
                                       @else
                                          <span class="badge bg-danger">Belum di Proses</span>
                                       @endif
                                    </td>
                                    <td>
                                       <div class="d-flex">
                                          @can('oman.show')
                                             <div>
                                                <a href="#" class="me-2 showOman"
                                                   kode_oman="{{ Crypt::encrypt($d->kode_oman) }}">
                                                   <i class="ti ti-file-description text-info"></i>
                                                </a>
                                             </div>
                                          @endcan

                                          @can('oman.delete')
                                             @if ($d->status_oman === '0')
                                                <div>
                                                   <form method="POST" name="deleteform"
                                                      class="deleteform"
                                                      action="{{ route('oman.delete', Crypt::encrypt($d->kode_oman)) }}">
                                                      @csrf
                                                      @method('DELETE')
                                                      <a href="#" class="delete-confirm ml-1">
                                                         <i class="ti ti-trash text-danger"></i>
                                                      </a>
                                                   </form>
                                                </div>
                                             @endif
                                          @endcan
                                       </div>
                                    </td>
                                 </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     <div style="float: right;">
                        {{-- {{ $oman_cabang->links() }} --}}
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<x-modal-form id="mdlCreate" size="modal-xl" show="loadCreate" title="Buat Oman " />
<x-modal-form id="mdlEdit" size="modal-xl" show="loadEdit" title="Edit Oman " />
<x-modal-form id="mdlDetail" size="modal-xl" show="loadDetail" title="Detail Oman" />
<x-modal-form id="mdleditOmancabang" show="loadeditOmancabang" title="Edit Oman Cabang" />
@endsection
@push('myscript')
<script>
   $(function() {
      $(document).on('show.bs.modal', '.modal', function() {
         const zIndex = 1090 + 10 * $('.modal:visible').length;
         $(this).css('z-index', zIndex);
         setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
            .addClass('modal-stack'));
      });

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


      $("#createOman").click(function(e) {
         $('#mdlCreate').modal("show");
         $("#loadCreate").html(loadingElement());
         $("#loadCreate").load('/oman/create');
      });

      $(".editOman").click(function(e) {
         const kode_oman = $(this).attr("kode_oman");
         $('#mdlEdit').modal("show");
         $("#loadEdit").html(loadingElement());
         $("#loadEdit").load('/oman/' + kode_oman + '/edit');
      });
      $(".showOman").click(function(e) {
         const kode_oman = $(this).attr("kode_oman");
         //alert(kode_oman);
         e.preventDefault();
         $('#mdlDetail').modal("show");
         $("#loadDetail").html(loadingElement());
         $("#loadDetail").load('/oman/' + kode_oman + '/show');
      });
   });
</script>
@endpush
