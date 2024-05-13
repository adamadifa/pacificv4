@extends('layouts.app')
@section('titlepage', 'Saldo Awal Gudang Cabang')

@section('content')
@section('navigasi')
   <span>Saldo Awal Gudang Cabang</span>
@endsection
<div class="row">
   <div class="col-lg-8 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('sagudangcabang.create')
               <a href="{{ route('sagudangcabang.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Buat Saldo Awal</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row mt-2">
               <div class="col-12">
                  <form action="{{ route('sagudangcabang.index') }}">
                     <div class="row">
                        @hasanyrole($roles_show_cabang)
                           <div class="col-lg-3 col-md-12 col-sm-12">
                              <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                 key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                 selected="{{ Request('kode_cabang_search') }}"
                                 select2="select2Kodecabangsearch" />
                           </div>

                        @endrole
                        <div class="col-lg-2 col-sm-12 col-md-12">
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
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <div class="form-group mb-3">
                              <select name="tahun" id="tahun" class="form-select">
                                 <option value="">Tahun</option>
                                 @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option
                                       @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                      @else
                                                      {{ date('Y') == $t ? 'selected' : '' }} @endif
                                       value="{{ $t }}">{{ $t }}</option>
                                 @endfor
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12">
                           <div class="form-group mb-3">
                              <select name="kondisi" id="kondisi" class="form-select">
                                 <option value="">GOOD / BAD </option>
                                 <option value="GS">GOOD STOK</option>
                                 <option value="BS">BAD STOK</option>
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
                              <th>Good/Bad</th>
                              <th>Cabang</th>
                              <th>Tanggal</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($saldo_awal as $d)
                              <tr>
                                 <td>{{ $d->kode_saldo_awal }}</td>
                                 <td>{{ $nama_bulan[$d->bulan] }}</td>
                                 <td>{{ $d->tahun }}</td>
                                 <td>
                                    @if ($d->kondisi == 'GS')
                                       <span class="badge bg-success">GOOD STOK</span>
                                    @else
                                       <span class="badge bg-danger">BAD STOK</span>
                                    @endif
                                 </td>
                                 <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                 <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('sagudangcabang.show')
                                          <div>
                                             <a href="#" class="me-2 btnShow"
                                                kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}">
                                                <i class="ti ti-file-description text-info"></i>
                                             </a>
                                          </div>
                                       @endcan
                                       @can('sagudangcabang.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('sagudangcabang.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
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
                     {{ $saldo_awal->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="Detail Saldo Awal " />
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

      $(".btnShow").click(function(e) {
         var kode_saldo_awal = $(this).attr("kode_saldo_awal");
         e.preventDefault();
         $('#modal').modal("show");
         $("#loadmodal").load('/sagudangcabang/' + kode_saldo_awal + '/show');
      });


   });
</script>
@endpush
