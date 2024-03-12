@extends('layouts.app')
@section('titlepage', 'Barang Masuk Produksi')

@section('content')
@section('navigasi')
    <span>Barang Masuk Produksi</span>
@endsection

<div class="row">
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpbj.create')
                    <a href="{{ route('barangmasukproduksi.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                        Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('barangmasukproduksi.index') }}">


                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Asal Barang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $bpbj->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdldetail" size="" show="loaddetail" title="Detail Saldo Awal " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".show").click(function(e) {
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/barangmasukproduksi/' + no_bukti + '/show');
        });
    });
</script>
@endpush
