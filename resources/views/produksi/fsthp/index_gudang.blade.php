@extends('layouts.app')
@section('titlepage', 'FSTHP')

@section('content')
@section('navigasi')
    <span>FSTHP</span>
@endsection
<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        @can('suratjalan.approve')
            <div class="alert alert-info alert-dismissible d-flex align-items-baseline" role="alert">
                <span class="alert-icon alert-icon-lg text-info me-2">
                    <i class="ti ti-info-circle ti-sm"></i>
                </span>
                <div class="d-flex flex-column ps-1">
                    <h5 class="alert-heading mb-2">Informasi</h5>
                    <p class="mb-0">
                        Silahkan Gunakan Icon <i class="ti ti-square-rounded-check text-success me-1 ms-1"></i> Untuk
                        Melakukan
                        Konfirmasi Penerimaan FSTHP
                    </p>
                    <p class="mb-0">
                        Silahkan Gunakan Icon <i class="ti ti-square-rounded-minus text-warning me-1 ms-1"></i> Untuk
                        Membatalkan Konfirmasi Penerimaan FSTHP !
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endcan
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudangjadi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @include('produksi.fsthp.index');
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdldetailFsthp" size="modal-lg" show="loaddetailFsthp" title="Detail FSTHP " />
@endsection
@push('myscript')
<script>
    $(function() {
        $(".showFsthp").click(function(e) {
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $('#mdldetailFsthp').modal("show");
            $("#loaddetailFsthp").load('/fsthp/' + no_mutasi + '/show');
        });
    });
</script>
@endpush
