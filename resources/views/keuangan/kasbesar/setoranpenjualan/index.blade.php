@extends('layouts.app')
@section('titlepage', 'Setoran Penjualan')

@section('content')
@section('navigasi')
   <span>Setoran Penjualan</span>
@endsection
<div class="row">
   <div class="col-lg-12">
      <div class="nav-align-top nav-tabs-shadow mb-4">
         @include('layouts.navigation_kasbesar')
         <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
               @can('setoranpenjualan.create')
                  <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                     Input Setoran Penjualan
                  </a>
               @endcan
               <div class="row mt-2">
                  <div class="col-12">
                     <form action="{{ route('setoranpenjualan.index') }}">
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
                                 <select name="kode_salesman_search" id="kode_salesman_search"
                                    class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group mb-3">
                                 <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
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
                        <table class="table  table-bordered">
                           <thead class="table-dark">
                              <tr>
                                 <th rowspan="2" class="align-middle">Tanggal</th>
                                 <th rowspan="2" class="align-middle">Salesman</th>
                                 <th colspan="2" class="text-center bg-success">Penjualan</th>
                                 <th rowspan="2" class="align-middle bg-success">Total LHP</th>
                                 <th colspan="5" class="text-center bg-danger">Setoran</th>
                                 <th rowspan="2" class="align-middle bg-danger">Total Setoran</th>
                                 <th rowspan="2" class="align-middle"></th>
                              </tr>
                              <tr>
                                 <th class="bg-success">Tunai</th>
                                 <th class="bg-success">Tagihan</th>

                                 <th class="bg-danger">Kertas</th>
                                 <th class="bg-danger">Logam</th>
                                 <th class="bg-danger">Giro</th>
                                 <th class="bg-danger">Transfer</th>
                                 <th class="bg-danger">Lainnya</th>
                              </tr>
                           </thead>
                           <tbody>
                              @php
                                 $subtotal_lhp_tunai = 0;
                                 $subtotal_lhp_tagihan = 0;
                                 $subtotal_total_lhp = 0;

                                 $subtotal_setoran_kertas = 0;
                                 $subtotal_setoran_logam = 0;
                                 $subtotal_setoran_lainnya = 0;
                                 $subtotal_setoran_transfer = 0;
                                 $subtotal_setoran_giro = 0;
                                 $subtotal_total_setoran = 0;

                              @endphp
                              @foreach ($setoran_penjualan as $key => $d)
                                 @php
                                    $next_tanggal = @$setoran_penjualan[$key + 1]->tanggal;
                                    $total_lhp = $d->lhp_tunai + $d->lhp_tagihan;
                                    $total_setoran = $d->setoran_kertas + $d->setoran_logam + $d->setoran_giro + $d->setoran_transfer + $d->setoran_lainnya;

                                    $subtotal_lhp_tunai += $d->lhp_tunai;
                                    $subtotal_lhp_tagihan += $d->lhp_tagihan;
                                    $subtotal_total_lhp += $total_lhp;

                                    $subtotal_setoran_kertas += $d->setoran_kertas;
                                    $subtotal_setoran_logam += $d->setoran_logam;
                                    $subtotal_setoran_lainnya += $d->setoran_lainnya;
                                    $subtotal_setoran_transfer += $d->setoran_transfer;
                                    $subtotal_setoran_giro += $d->setoran_giro;
                                    $subtotal_total_setoran += $total_setoran;
                                 @endphp
                                 <tr>
                                    <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                    <td>{{ $d->nama_salesman }}</td>
                                    <td class="text-end">{{ formatAngka($d->lhp_tunai) }}</td>
                                    <td class="text-end">{{ formatAngka($d->lhp_tagihan) }}</td>
                                    <td class="text-end">{{ formatAngka($total_lhp) }}</td>
                                    <td class="text-end">{{ formatAngka($d->setoran_kertas) }}</td>
                                    <td class="text-end">{{ formatAngka($d->setoran_logam) }}</td>
                                    <td class="text-end">{{ formatAngka($d->setoran_giro) }}</td>
                                    <td class="text-end">{{ formatAngka($d->setoran_transfer) }}</td>
                                    <td class="text-end">{{ formatAngka($d->setoran_lainnya) }}</td>
                                    <td class="text-end">{{ formatAngka($total_setoran) }}</td>
                                 </tr>
                                 @if ($d->tanggal != $next_tanggal)
                                    <tr class="table-dark">
                                       <td colspan='2'>TOTAL</td>
                                       <td class="text-end">{{ formatAngka($subtotal_lhp_tunai) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_lhp_tagihan) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_total_lhp) }}</td>

                                       <td class="text-end">{{ formatAngka($subtotal_setoran_kertas) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_setoran_logam) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_setoran_lainnya) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_setoran_transfer) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_setoran_giro) }}</td>
                                       <td class="text-end">{{ formatAngka($subtotal_total_setoran) }}</td>
                                    </tr>
                                    @php
                                       $subtotal_lhp_tunai = 0;
                                       $subtotal_lhp_tagihan = 0;
                                       $subtotal_total_lhp = 0;

                                       $subtotal_setoran_kertas = 0;
                                       $subtotal_setoran_logam = 0;
                                       $subtotal_setoran_lainnya = 0;
                                       $subtotal_setoran_transfer = 0;
                                       $subtotal_setoran_giro = 0;
                                       $subtotal_total_setoran = 0;
                                    @endphp
                                 @endif
                              @endforeach
                           </tbody>
                        </table>
                     </div>

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
      $("#btnCreate").click(function(e) {
         e.preventDefault();
         $("#modal").modal("show");
         $("#modal").find(".modal-title").text('Input Pembayaran Setoran');
         $("#loadmodal").load('/setoranpenjualan/create');
      });
   });
</script>
@endpush
