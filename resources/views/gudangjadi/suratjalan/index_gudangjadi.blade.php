@extends('layouts.app')
@section('titlepage', 'Surat Jalan')

@section('content')
@section('navigasi')
   <span>Surat Jalan</span>
@endsection
<div class="row">
   <div class="col-lg-10 col-md-12 col-sm-12">
      @can('suratjalan.approve')
         <div class="alert alert-info alert-dismissible d-flex align-items-baseline" role="alert">
            <span class="alert-icon alert-icon-lg text-info me-2">
               <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
               <h5 class="alert-heading mb-2">Informasi</h5>
               <p class="mb-0">
                  Silahkan Gunakan Icon <i class="ti ti-external-link text-primary me-1 ms-1"></i> Untuk Melakukan
                  Penerimaan Data Surat Jalan
               </p>
               <p class="mb-0">
                  Silahkan Gunakan Icon <i class="ti ti-square-rounded-minus text-warning me-1 ms-1"></i> Untuk
                  Membatalkan Penerimaan Surat Jalan !
               </p>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
         </div>
      @endcan
      <div class="nav-align-top nav-tabs-shadow mb-4">
         @include('layouts.navigation_mutasigudangjadi')
         <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
               @can('suratjalan.create')
                  <a href="{{ route('permintaankiriman.index') }}" class="btn btn-primary"><i
                        class="fa fa-plus me-2"></i>
                     Buat Surat Jalan</a>
               @endcan
               @include('gudangjadi.suratjalan.index')
            </div>
         </div>
      </div>
   </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/suratjalan.js') }}"></script>
@endpush
