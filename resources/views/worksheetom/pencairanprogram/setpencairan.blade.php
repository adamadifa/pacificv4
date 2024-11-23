@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('pencairanprogram.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>Kode Pencairan</th>
                                <td class="text-end">{{ $pencairanprogram->kode_pencairan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($pencairanprogram->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Periode Penjualan</th>
                                <td class="text-end">{{ $namabulan[$pencairanprogram->bulan] }} {{ $pencairanprogram->tahun }}</td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td class="text-end">{{ $pencairanprogram->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $pencairanprogram->kode_cabang }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nik</th>
                                    <th>Nama Karyawan</th>
                                    <th>Dept</th>
                                    <th>Grup</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="loadpelanggan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let bulan = "{{ $pencairanprogram->bulan }}";
            let tahun = "{{ $pencairanprogram->tahun }}";
            let kode_cabang = "{{ $pencairanprogram->kode_cabang }}";
            let kode_program = "{{ $pencairanprogram->kode_program }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Pelanggan");
            $("#loadmodal").load("/pencairanprogram/" + bulan + "/" + tahun + "/" + kode_cabang + "/" + kode_program +
                "/tambahpelanggan");

        });
    });
</script>
@endpush
