@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program Ikatan</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('pencairanprogramikt.create')
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
                                <th>No. Dokumen</th>
                                <td class="text-end">{{ $pencairanprogram->nomor_dokumen }}</td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td class="text-end">{{ $pencairanprogram->nama_program }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ strtoupper($pencairanprogram->nama_cabang) }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="text-center" valign="middle">Nik</th>
                                    <th rowspan="2" class="text-center" valign="middle">Kode Pel</th>
                                    <th rowspan="2" valign="middle">Nama Pelanggan</th>
                                    <th rowspan="2" class="text-center" valign="middle">Qty</th>
                                    <th colspan="2" class="text-center" valign="middle">Diskon</th>
                                    <th rowspan="2" class="text-center" valign="middle">Cashback</th>
                                    <th rowspan="2" class="text-center" valign="middle">#</th>
                                </tr>
                                <tr>
                                    <th>Reguler</th>
                                    <th>Kumulatif</th>
                                </tr>
                            </thead>
                            <tbody id="loaddetailpencairan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Pelanggan");
            $("#loadmodal").load("/pencairanprogramikatan/" + kode_pencairan + "/tambahpelanggan");
        });
    });
</script>
@endpush
