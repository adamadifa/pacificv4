@extends('layouts.app')
@section('titlepage', 'Atur Ajuan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Ajuan Program Ikatan</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('ajuanprogramikatan.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>No. Pengajuan</th>
                                <td class="text-end">{{ $programikatan->no_pengajuan }}</td>
                            </tr>
                            <tr>
                                <th>No. Dokumen</th>
                                <td class="text-end">{{ $programikatan->nomor_dokumen }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($programikatan->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Periode Penjualan</th>
                                <td class="text-end">{{ DateToIndo($programikatan->periode_dari) }} s.d
                                    {{ DateToIndo($programikatan->periode_sampai) }}</td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td class="text-end">{{ $programikatan->nama_program }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $programikatan->kode_cabang }}</td>
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
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let no_pengajuan = "{{ Crypt::encrypt($programikatan->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/ajuanprogramikatan/" + no_pengajuan + "/tambahpelanggan");
        });
    });
</script>
@endpush
