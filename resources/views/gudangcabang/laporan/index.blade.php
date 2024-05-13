@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Cabang')

@section('content')

@section('navigasi')
   <span>Laporan Gudang Cabang</span>
@endsection
<div class="row">
   <div class="col-xl-6 col-md-12 col-sm-12">
      <div class="nav-align-left nav-tabs-shadow mb-4">
         <ul class="nav nav-tabs" role="tablist">
            @can('gc.goodstok')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                     data-bs-target="#goodstok" aria-controls="goodstok" aria-selected="false" tabindex="-1">
                     Lap. Persediaan GS
                  </button>
               </li>
            @endcan
            @can('gc.badstok')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#badstok" aria-controls="badstok" aria-selected="false" tabindex="-1">
                     Lap. Persediaan BS
                  </button>
               </li>
            @endcan
            @can('gc.rekappersediaan')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#rekappersediaan" aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                     Rekap Persediaan
                  </button>
               </li>
            @endcan
            @can('gc.mutasidpb')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#mutasidpb" aria-controls="mutasidpb" aria-selected="false" tabindex="-1">
                     Mutasi DPB
                  </button>
               </li>
            @endcan
            @can('gc.rekonsiliasibj')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#rekonsiliasibj" aria-controls="rekonsiliasibj" aria-selected="false" tabindex="-1">
                     Rekonsiliasi BJ
                  </button>
               </li>
            @endcan
         </ul>
         <div class="tab-content">
            <!-- Laporan Persediaan-->
            @can('gc.goodstok')
               <div class="tab-pane fade active show" id="goodstok" role="tabpanel">
                  @include('gudangcabang.laporan.goodstok')
               </div>
            @endcan

            @can('gc.badstok')
               <div class="tab-pane fade" id="badstok" role="tabpanel">
                  @include('gudangcabang.laporan.badstok')
               </div>
            @endcan

            @can('gc.rekappersediaan')
               <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                  @include('gudangcabang.laporan.rekappersediaan')
               </div>
            @endcan

            @can('gc.mutasidpb')
               <div class="tab-pane fade" id="mutasidpb" role="tabpanel">
                  @include('gudangcabang.laporan.mutasidpb')
               </div>
            @endcan

            @can('gc.rekonsiliasibj')
               <div class="tab-pane fade" id="rekonsiliasibj" role="tabpanel">
                  @include('gudangcabang.laporan.rekonsiliasibj')
               </div>
            @endcan
         </div>
      </div>
   </div>

</div>
@endsection


@push('myscript')
<script>
   $(function() {




   });
</script>
@endpush
