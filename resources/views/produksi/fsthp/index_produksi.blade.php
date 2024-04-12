@extends('layouts.app')
@section('titlepage', 'Form Serah Terima Hasil Produksi (FSTHP)')

@section('content')
@section('navigasi')
    <span>FSTHP</span>
@endsection
<div class="row">
    <div class="col-lg-7 col-sm-12 col-md-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasiproduksi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @include('produksi.fsthp.index');
                </div>
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
