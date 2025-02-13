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
                <div class="d-flex justify-content-between">
                    <a href="{{ route('pencairanprogramikatan.index') }}" class="me-1 btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                    @can('pencairanprogramikt.create')
                        @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $pencairanprogram->rsm == null)
                            @if ($pencairanprogram->status == 0)
                                <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                            @endif
                        @endif
                        @if ($user->hasRole('super admin'))
                            <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                        @endif
                    @endcan
                </div>

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

                                    <th>Pembayaran</th>
                                    <th>No. Rekening</th>
                                    <th>Pemilik</th>
                                    <th>Bank</th>
                                    <th>Total</th>
                                    <th><i class="ti ti-file-description"></i></th>
                                    <th><i class="ti ti-moneybag"></i></th>
                                    <th>#</th>
                                </tr>

                            </thead>
                            <tbody id="loaddetailpencairan">
                                @php
                                    $metode_pembayaran = [
                                        'TN' => 'Tunai',
                                        'TF' => 'Transfer',
                                        'VC' => 'Voucher',
                                    ];
                                    $subtotal_reward = 0;
                                    $grandtotal_reward = 0;
                                @endphp
                                @foreach ($detail as $key => $d)
                                    @php
                                        $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                                        $total_reward = $d->tipe_reward == '1' ? $d->reward * $d->jumlah : $d->reward;
                                        $total_reward = $total_reward > 1000000 ? 1000000 : $total_reward;
                                        $subtotal_reward += $total_reward;
                                        $grandtotal_reward += $total_reward;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kode_pelanggan }}</td>
                                        <td>{{ $d->nama_pelanggan }}</td>
                                        <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                                        <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                                        <td class="text-end">{{ formatAngka($d->reward) }}</td>

                                        <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>

                                        <td>{{ $d->no_rekening }}</td>
                                        <td>{{ $d->pemilik_rekening }}</td>
                                        <td>{{ $d->bank }}</td>
                                        <td class="text-end">{{ formatAngka($total_reward) }}</td>

                                        <td>
                                            @if (!empty($d->bukti_transfer))
                                                <a href="{{ url($d->bukti_transfer) }}" target="_blank">
                                                    <i class="ti ti-receipt text-success"></i>
                                                </a>
                                            @else
                                                <i class="ti ti-hourglass-empty text-warning"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($d->status_pencairan == '1')
                                                <i class="ti ti-checks text-success"></i>
                                            @else
                                                <i class="ti ti-hourglass-empty text-warning"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="#" class="btnDetailfaktur me-1" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                                @can('pencairanprogramikt.upload')
                                                    <a href="#" kode_pencairan="{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}"
                                                        kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnUpload">
                                                        <i class="ti ti-upload text-primary"></i>
                                                    </a>
                                                @endcan
                                                @can('pencairanprogramikt.delete')
                                                    @if ($pencairanprogram->status == '0')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('pencairanprogramikatan.deletepelanggan', [Crypt::encrypt($pencairanprogram->kode_pencairan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @if ($d->metode_pembayaran != $next_metode_pembayaran)
                                        <tr class="table-dark">
                                            <td colspan="10">TOTAL REWARD </td>
                                            <td class="text-end">{{ formatAngka($subtotal_reward) }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php
                                            $subtotal_reward = 0;
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="10">GRAND TOTAL REWARD </td>
                                    <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalUpload" size="" show="loadmodalupload" title="" />
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

        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            let kode_pelanggan = $(this).attr("kode_pelanggan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogramikatan/" + kode_pencairan + "/" + kode_pelanggan + "/upload");
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogramikatan/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });
    });
</script>
@endpush
