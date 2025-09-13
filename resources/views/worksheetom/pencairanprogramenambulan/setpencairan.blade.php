@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program Ikatan Enambulan')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program Ikatan Enambulan</span>
@endsection
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" rel="stylesheet">

<style>
    /* Supaya tidak ada scroll horizontal berlebih */
    div.dataTables_wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    thead th {
        background-color: #002e65 !important;
        color: white !important;
    }

    #example_filter {
        margin-bottom: 5px;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('pencairanprogramikatan.index') }}" class="me-1 btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                    @can('pencairanprogramikt.create')
                        @if (
                            $user->hasRole(['operation manager', 'sales marketing manager', 'regional operation manager']) &&
                                $pencairanprogram->rsm == null)
                            @if ($pencairanprogram->status == 0)
                                <a href="#" id="btnCreate" class="btn btn-primary"><i
                                        class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                            @endif
                        @endif
                        @if ($user->hasRole('super admin'))
                            <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i>
                                Tambah Pelanggan</a>
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
                                <td class="text-end">
                                    @if ($pencairanprogram->semester == 1)
                                        @php
                                            $periode_start = $pencairanprogram->tahun . '-01-01';
                                            $periode_end = date(
                                                'Y-m-t',
                                                strtotime($pencairanprogram->tahun . '-06-01'),
                                            );
                                        @endphp
                                    @endif
                                    @if ($pencairanprogram->semester == 2)
                                        @php
                                            $periode_start = $pencairanprogram->tahun . '-07-01';
                                            $periode_end = date(
                                                'Y-m-t',
                                                strtotime($pencairanprogram->tahun . '-12-01'),
                                            );
                                        @endphp
                                    @endif
                                    {{ DateToIndo($periode_start) }} s/d {{ DateToIndo($periode_end) }}
                                </td>
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
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="example" class="display nowrap table table-striped table-bordered"
                                style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama Pelanggan</th>

                                        <th class="text-center">Target</th>
                                        <th class="text-center">Realisasi</th>
                                        <th class="text-center">Reward</th>

                                        <th>Pembayaran</th>
                                        <th>No. Rekening</th>
                                        <th>Pemilik</th>
                                        <th>Bank</th>
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
                                        $bb_dep = ['PRIK004', 'PRIK001'];
                                    @endphp
                                    @foreach ($detail as $key => $d)
                                        @php
                                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                                            $total_reward = $d->total_reward;
                                            $subtotal_reward += $total_reward;
                                            $grandtotal_reward += $total_reward;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_pelanggan }}</td>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>

                                            <td class="text-center">
                                                <a href="#" class="btnDetailfaktur"
                                                    kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                                                    {{ formatAngka($d->jumlah) }}
                                                </a>
                                            </td>
                                            {{-- <td class="text-end">{{ formatAngka($d->reward_tunai) }}</td>
                                            <td class="text-end">{{ formatAngka($d->reward_kredit) }}</td> --}}
                                            <td class="text-end">{{ formatAngka($total_reward) }}</td>
                                            <td>{{ $d->metode_pembayaran }}</td>

                                            <td>{{ $d->no_rekening }}</td>
                                            <td>{{ $d->pemilik_rekening }}</td>
                                            <td>{{ $d->bank }}</td>


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
                                                    {{-- <a href="#" class="btnDetailfaktur me-1" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                                                    <i class="ti ti-file-description"></i>
                                                </a> --}}
                                                    {{-- @can('pencairanprogramikt.upload')
                                                        <a href="#" kode_pencairan="{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}"
                                                            kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnUpload">
                                                            <i class="ti ti-upload text-primary"></i>
                                                        </a>
                                                    @endcan --}}
                                                    @can('pencairanprogramenambulan.delete')
                                                        @if ($pencairanprogram->status == '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('pencairanprogramenambulan.deletepelanggan', [Crypt::encrypt($pencairanprogram->kode_pencairan), Crypt::encrypt($d->kode_pelanggan)]) }}">
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
                                        {{-- @if ($d->metode_pembayaran != $next_metode_pembayaran)
                                            <tr class="table-dark">
                                                <td colspan="12">TOTAL REWARD </td>
                                                <td class="text-end">{{ formatAngka($subtotal_reward) }}</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            @php
                                                $subtotal_reward = 0;
                                            @endphp
                                        @endif --}}
                                    @endforeach
                                </tbody>
                                {{-- <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="12">GRAND TOTAL REWARD </td>
                                        <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
                                        <td colspan="8"></td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-fullscreen" show="loadmodal" title="" />
<x-modal-form id="modalUpload" size="" show="loadmodalupload" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollX: true, // Aktifkan horizontal scroll
            scrollCollapse: true,
            paging: false, // Nonaktifkan pagination agar tabel bisa terlihat penuh
            fixedColumns: {
                left: 3, // Membekukan 3 kolom pertama
                right: 3 // Membekukan 3 kolom terakhir
            }
        });
    });
</script>
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Pelanggan");
            $("#loadmodal").load("/pencairanprogramenambulan/" + kode_pencairan + "/tambahpelanggan");
        });

        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            let kode_pelanggan = $(this).attr("kode_pelanggan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogramenambulan/" + kode_pencairan + "/" +
                kode_pelanggan + "/upload");
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogramenambulan/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });
    });
</script>
@endpush
