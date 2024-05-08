@extends('layouts.app')
@section('titlepage', 'Permintaan Kiriman')

@section('content')
@section('navigasi')
   <span>Permintaan Kiriman</span>
@endsection
<div class="row">
   <div class="col-lg-10 col-sm-12 col-xs-12">

      <div class="alert alert-info alert-dismissible d-flex align-items-baseline" role="alert">
         <span class="alert-icon alert-icon-lg text-info me-2">
            <i class="ti ti-info-circle ti-sm"></i>
         </span>
         <div class="d-flex flex-column ps-1">
            <h5 class="alert-heading mb-2">Informasi</h5>
            <p class="mb-0">
               Silahkan Gunakan Icon <i class="ti ti-external-link text-primary me-1 ms-1"></i> Untuk membuat Surat
               Jalan !
            </p>
            <p class="mb-0">
               Silahkan Gunakan Icon <i class="ti ti-square-rounded-minus text-warning me-1 ms-1"></i> Untuk
               Membatalkan
               Surat Jalan !
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
      </div>
      <div class="card">
         <div class="card-header">
            @can('permintaankiriman.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                  Permintaan</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('permintaankiriman.index') }}">
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
                           <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                              key="kode_cabang" textShow="nama_cabang" upperCase="true"
                              selected="{{ Request('kode_cabang_search') }}" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group mb-3">
                              <select name="status_search" id="status_search" class="form-select">
                                 <option value="">Smua Status</option>
                                 <option {{ Request('status_search') == '0|pk' ? 'selected' : '' }}
                                    value="0|pk">Belum Di Proses</option>
                                 <option {{ Request('status_search') == '1|pk' ? 'selected' : '' }}
                                    value="1|pk">Sudah Di Proses Gudang</option>
                                 <option {{ Request('status_search') == '0|sj' ? 'selected' : '' }}
                                    value="0|sj">
                                    Belum Diterima Cabang</option>
                                 <option {{ Request('status_search') == '1|sj' ? 'selected' : '' }}
                                    value="1|sj">Sudah Diterima Cabang</option>
                                 <option {{ Request('status_search') == '2|sj' ? 'selected' : '' }}
                                    value="2|sj">Transit Out</option>
                              </select>
                           </div>
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
                     <table class="table">
                        <thead class="table-dark">
                           <tr>
                              <th>No. Permintaan</th>
                              <th>Tanggal</th>
                              <th>Cabang</th>
                              <th>Keterangan</th>
                              <th>Status</th>
                              <th>Salesman</th>
                              {{-- <th>No. SJ</th>
                                        <th>No. Dok</th>
                                        <th>Tanggal SJ</th> --}}
                              <th>Status SJ</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($pk as $d)
                              <tr>
                                 <td>{{ $d->no_permintaan }}</td>
                                 <td>
                                    {{ date('d-m-Y', strtotime($d->tanggal)) }}
                                 </td>
                                 <td>{{ $d->kode_cabang }}</td>
                                 <td>{{ $d->keterangan }}</td>
                                 <td>
                                    @if ($d->status == 1)
                                       <span class="badge badge-sm bg-success">
                                          {{ $d->no_mutasi }}
                                       </span>
                                    @else
                                       <span class="badge badge bg-danger">
                                          Belum Di Proses
                                       </span>
                                    @endif
                                 </td>
                                 <td>

                                    @php
                                       $nama_sales = explode(' ', $d->nama_salesman);
                                    @endphp
                                    {{ $nama_sales[0] }}
                                 </td>
                                 {{-- <td>
                                                @if (!empty($d->no_mutasi))
                                                    {{ $d->no_mutasi }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->no_dok))
                                                    {{ $d->no_dok }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->no_mutasi))
                                                    {{ date('d-m-Y', strtotime($d->tanggal_surat_jalan)) }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td> --}}
                                 <td>
                                    @if ($d->status == 1)
                                       @if ($d->status_surat_jalan == 0)
                                          <span class="badge bg-danger">Belum Diterima Cabang</span>
                                       @elseif($d->status_surat_jalan == 1)
                                          <span class="badge bg-success">Sudah Diterima Cabang</span>
                                       @elseif($d->status_surat_jalan == 2)
                                          <span class="badge bg-info">Transit Out</span>
                                       @endif
                                    @else
                                       <i class="ti ti-refresh text-warning"></i>
                                    @endif
                                 </td>
                                 <td>
                                    <div class="d-flex">
                                       @can('permintaankiriman.edit')
                                          @if ($d->status == 0)
                                             <div>
                                                <a href="#" class="me-2 btnEdit"
                                                   no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                   <i class="ti ti-edit text-success"></i>
                                                </a>
                                             </div>
                                          @endif
                                       @endcan
                                       @can('permintaankiriman.show')
                                          <div>
                                             <a href="#" class="me-2 btnShow"
                                                no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                <i class="ti ti-file-description text-info"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('permintaankiriman.delete')
                                          @if ($d->status == 0)
                                             <div>
                                                <form method="POST" name="deleteform" class="deleteform"
                                                   action="{{ route('permintaankiriman.delete', Crypt::encrypt($d->no_permintaan)) }}">
                                                   @csrf
                                                   @method('DELETE')
                                                   <a href="#" class="delete-confirm me-1">
                                                      <i class="ti ti-trash text-danger"></i>
                                                   </a>
                                                </form>
                                             </div>
                                          @endif
                                       @endcan

                                       @can('suratjalan.create')
                                          @if ($d->status === '0')
                                             <div>
                                                <a href="#" alt="Buat Surat Jalan"
                                                   no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}"
                                                   class="btnCreateSuratjalan">
                                                   <i class="ti ti-external-link primary "></i>
                                                </a>
                                             </div>
                                          @endif
                                       @endcan

                                       @can('suratjalan.delete')
                                          @if ($d->status == '1' && $d->status_surat_jalan === '0')
                                             <div>
                                                <form method="POST" name="deleteform" class="deleteform"
                                                   action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                   @csrf
                                                   @method('DELETE')
                                                   <a href="#" class="delete-confirm me-1">
                                                      <i
                                                         class="ti ti-square-rounded-minus text-warning"></i>
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
                     {{ $pk->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="mdlCreate" show="loadCreate" title="Buat Permintaan" />
<x-modal-form id="mdlEdit" show="loadEdit" title="Edit Permintaan" />
<x-modal-form id="mdlDetail" show="loadDetail" title="Detail Permintaan" />
<x-modal-form id="mdlCreateSuratjalan" show="loadCreateSuratjalan" title="Buat Surat Jalan" />
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
         $('#mdlCreate').modal("show");
         $("#loadEdit").html('');
         $("#loadCreateSuratjalan").html('');
         $("#loadCreate").html(loadingElement());
         $("#loadCreate").load("{{ route('permintaankiriman.create') }}");
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         const no_permintaan = $(this).attr('no_permintaan');
         $('#mdlDetail').modal("show");
         $("#loadDetail").html(loadingElement());
         $("#loadDetail").load(`/permintaankiriman/${no_permintaan}/show`);
      });

      $(".btnEdit").click(function(e) {
         const no_permintaan = $(this).attr("no_permintaan");
         e.preventDefault();
         $("#loadCreate").html('');
         $("#loadCreateSuratjalan").html('');
         $('#mdlEdit').modal("show");
         $("#loadEdit").html(loadingElement());
         $("#loadEdit").load(`/permintaankiriman/${no_permintaan}/edit`);
      });

      $(".btnCreateSuratjalan").click(function(e) {
         e.preventDefault();
         const no_permintaan = $(this).attr("no_permintaan");
         $("#loadCreate").html('');
         $("#loadEdit").html('');
         $('#mdlCreateSuratjalan').modal("show");
         $("#loadCreateSuratjalan").html(loadingElement());
         $("#loadCreateSuratjalan").load(`/suratjalan/${no_permintaan}/create`);
      });
   });
</script>
@endpush
