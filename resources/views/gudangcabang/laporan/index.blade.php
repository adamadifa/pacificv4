@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Cabang')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Gudang Cabang</h4>
            <small class="text-muted">Laporan mutasi dan persediaan gudang cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Cabang</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report me-1"></i>Laporan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gc.goodstok')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#goodstok"
                            aria-controls="goodstok" aria-selected="false" tabindex="-1">
                            <i class="ti ti-box me-2"></i> Lap. Persediaan GS
                        </button>
                    </li>
                @endcan
                @can('gc.badstok')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#badstok"
                            aria-controls="badstok" aria-selected="false" tabindex="-1">
                            <i class="ti ti-package-off me-2"></i> Lap. Persediaan BS
                        </button>
                    </li>
                @endcan
                @can('gc.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-analytics me-2"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('gc.mutasidpb')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#mutasidpb"
                            aria-controls="mutasidpb" aria-selected="false" tabindex="-1">
                            <i class="ti ti-truck-delivery me-2"></i> Mutasi DPB
                        </button>
                    </li>
                @endcan
                @can('gc.rekonsiliasibj')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekonsiliasibj"
                            aria-controls="rekonsiliasibj" aria-selected="false" tabindex="-1">
                            <i class="ti ti-clipboard-check me-2"></i> Rekonsiliasi BJ
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                <!-- Laporan Persediaan-->
                @can('gc.goodstok')
                    <div class="tab-pane fade active show" id="goodstok" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Laporan Persediaan GS</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangcabang.laporan.goodstok')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gc.badstok')
                    <div class="tab-pane fade" id="badstok" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-off me-2"></i>Laporan Persediaan BS</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangcabang.laporan.badstok')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gc.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-analytics me-2"></i>Rekap Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangcabang.laporan.rekappersediaan')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gc.mutasidpb')
                    <div class="tab-pane fade" id="mutasidpb" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck-delivery me-2"></i>Mutasi DPB</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangcabang.laporan.mutasidpb')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gc.rekonsiliasibj')
                    <div class="tab-pane fade" id="rekonsiliasibj" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-check me-2"></i>Rekonsiliasi BJ</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangcabang.laporan.rekonsiliasibj')
                            </div>
                        </div>
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
