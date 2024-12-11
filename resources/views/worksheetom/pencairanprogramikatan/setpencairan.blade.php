@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program Ikatan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('pencairanprogramikt.create')
                    @if ($pencairanprogram->status == 0)
                        <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                    @endif
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
                                    <th>No.</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Realisasi</th>
                                    <th>Reward</th>
                                    <th>Budget</th>
                                    <th>Pembayaran</th>
                                    <th>No. Rekening</th>
                                    <th>Pemilik</th>
                                    <th>Bank</th>
                                    <th>Total Reward</th>
                                </tr>

                            </thead>
                            <tbody id="loaddetailpencairan">
                                @php
                                    $metode_pembayaran = [
                                        'TN' => 'Tunai',
                                        'TF' => 'Transfer',
                                        'VC' => 'Voucher',
                                    ];
                                @endphp
                                @foreach ($detail as $key => $d)
                                    @php
                                        $next_tanggal = @$setoran_penjualan[$key + 1]->tanggal;
                                        $total_reward = $d->reward * $d->jumlah;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kode_pelanggan }}</td>
                                        <td>{{ $d->nama_pelanggan }}</td>
                                        <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                                        <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                                        <td class="text-end">{{ formatAngka($d->reward) }}</td>
                                        <td class="text-center">{{ $d->budget }}</td>
                                        <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                                        <td>{{ $d->no_rekening }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end">{{ formatAngka($total_reward) }}</td>
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
