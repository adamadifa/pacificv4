@extends('layouts.app')
@section('titlepage', 'Surat Jalan')

@section('content')
@section('navigasi')
    <span>Surat Jalan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudangjadi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('suratjalan.create')
                        <a href="{{ route('permintaankiriman.index') }}" class="btn btn-primary"><i
                                class="fa fa-plus me-2"></i>
                            Buat Surat Jalan</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('suratjalan.index') }}">


                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No. Surat Jalan</th>
                                            <th>No. Dokumen</th>
                                            <th>Tanggal</th>
                                            <th>Cabang</th>
                                            <th>Status</th>
                                            <th>Tanggal Diterima</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($surat_jalan as $d)
                                            <tr>
                                                <td>{{ $d->no_mutasi }}</td>
                                                <td>{{ $d->no_dok }}</td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                            </tr>
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

<x-modal-form id="mdldetail" size="" show="loaddetail" title="Detail Saldo Awal " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".showSaldoawal").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/sabarangproduksi/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
