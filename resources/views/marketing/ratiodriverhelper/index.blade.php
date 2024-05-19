@extends('layouts.app')
@section('titlepage', 'Ratio Driver Helper')

@section('content')
@section('navigasi')
   <span>Ratio Driver Helper</span>
@endsection
<div class="col-lg-8">
   <div class="nav-align-top nav-tabs-shadow mb-4">
      @include('layouts.navigation_targetkomisi')
      <div class="tab-content">
         <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
            @can('ratiodriverhelper.create')
               <a href="#" class="btn btn-primary btnCreate"><i class="fa fa-plus me-2"></i> Buat Ratio </a>
            @endcan
            <div class="row mt-2">
               <div class="col-12">
                  <form action="{{ route('ratiodriverhelper.index') }}">
                     <div class="row">
                        @hasanyrole($roles_show_cabang)
                           <div class="col-lg-4 col-md-12 col-sm-12">
                              <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                 key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                 selected="{{ Request('kode_cabang_search') }}"
                                 select2="select2Kodecabangsearch" />
                           </div>

                        @endrole
                        <div class="col-lg-3 col-sm-12 col-md-12">
                           <div class="form-group mb-3">
                              <select name="bulan" id="bulan" class="form-select">
                                 <option value="">Bulan</option>
                                 @foreach ($list_bulan as $d)
                                    <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                       value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12">
                           <div class="form-group mb-3">
                              <select name="tahun" id="tahun" class="form-select">
                                 <option value="">Tahun</option>
                                 @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option
                                       @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                @else {{ date('Y') == $t ? 'selected' : '' }} @endif
                                       value="{{ $t }}">{{ $t }}</option>
                                 @endfor
                              </select>
                           </div>
                        </div>

                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                              <th>Cabang</th>
                              <th>Berlaku</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($ratiodriverhelper as $d)
                              <tr>
                                 <td>{{ $d->kode_ratio }}</td>
                                 <td>{{ $nama_bulan[$d->bulan] }}</td>
                                 <td>{{ $d->tahun }}</td>
                                 <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                 <td>{{ DateToIndo($d->tanggal_berlaku) }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('ratiodriverhelper.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                kode_ratio="{{ Crypt::encrypt($d->kode_ratio) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('ratiodriverhelper.show')
                                          <div>
                                             <a href="#" class="me-2 btnShow"
                                                kode_ratio="{{ Crypt::encrypt($d->kode_ratio) }}">
                                                <i class="ti ti-file-description text-info"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('ratiodriverhelper.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('ratiodriverhelper.delete', Crypt::encrypt($d->kode_ratio)) }}">
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
                     {{ $ratiodriverhelper->links() }}
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
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
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

      $(".btnCreate").click(function(e) {
         e.preventDefault();
         $('#modal').modal("show");
         $("#loadmodal").load("{{ route('ratiodriverhelper.create') }}");
         $(".modal-title").text("Buat Ratio");
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         const kode_ratio = $(this).attr("kode_ratio");
         $('#modal').modal("show");
         $("#loadmodal").load(`/ratiodriverhelper/${kode_ratio}`);
         $(".modal-title").text("Detail Ratio Driver Helper");
      });


      $(".btnEdit").click(function(e) {
         e.preventDefault();
         const kode_ratio = $(this).attr("kode_ratio");
         $('#modal').modal("show");
         $("#loadmodal").load(`/ratiodriverhelper/${kode_ratio}/edit`);
         $(".modal-title").text("Edit Ratio Driver Helpar");
      });
   });
</script>
@endpush
