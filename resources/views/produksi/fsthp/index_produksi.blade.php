@extends('layouts.app')
@section('titlepage', 'Form Serah Terima Hasil Produksi (FSTHP)')

@section('content')
@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Form Serah Terima Hasil Produksi (FSTHP)</h4>
            <small class="text-muted">Mengelola data penyerahan hasil produksi ke gudang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Produksi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-export me-1"></i>FSTHP</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-7 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasiproduksi')
        </div>

        {{-- Tab Content / Data Card Area --}}
        <div class="tab-content p-0 shadow-none bg-transparent">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                @include('produksi.fsthp.index')
            </div>
        </div>
    </div>
</div>


<x-modal-form id="mdlcreateFsthp" size="" show="loadcreateFsthp" title="Tambah FSTHP " />
<x-modal-form id="mdldetailFsthp" size="modal-lg" show="loaddetailFsthp" title="Detail FSTHP " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateFsthp").click(function(e) {
            $('#mdlcreateFsthp').modal("show");
            $("#loadcreateFsthp").load('/fsthp/create');
        });

        $(".showFsthp").click(function(e) {
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $('#mdldetailFsthp').modal("show");
            $("#loaddetailFsthp").load('/fsthp/' + no_mutasi + '/show');
        });
    });
</script>
@endpush
