@extends('layouts.app')
@section('titlepage', 'FSTHP')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">FSTHP</h4>
            <small class="text-muted">Konfirmasi penerimaan FSTHP dari produksi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Jadi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-import me-1"></i>FSTHP</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudangjadi')
        </div>

        @can('suratjalan.approve')
            <div class="alert alert-info alert-dismissible d-flex align-items-baseline shadow-sm mb-3" role="alert">
                <span class="alert-icon alert-icon-lg text-info me-2">
                    <i class="ti ti-info-circle ti-md"></i>
                </span>
                <div class="d-flex flex-column ps-1">
                    <h5 class="alert-heading mb-1">Informasi</h5>
                    <p class="mb-0">
                        Gunakan Icon <i class="ti ti-square-rounded-check text-success mx-1"></i> untuk konfirmasi penerimaan.
                    </p>
                    <p class="mb-0">
                        Gunakan Icon <i class="ti ti-square-rounded-minus text-warning mx-1"></i> untuk membatalkan konfirmasi.
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endcan

        @include('produksi.fsthp.index')
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
