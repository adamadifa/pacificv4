@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program Ikatan</span>
@endsection
<div class="row">
    <div class="col-12">
        {{-- Toolbar & Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('pencairanprogramikatan2026.index') }}" class="btn btn-label-danger">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
            @can('pencairanprogramikatan2026.create')
                @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $pencairanprogram->rsm == null)
                    @if ($pencairanprogram->status == 0)
                        <a href="#" id="btnCreate" class="btn btn-primary shadow-sm"><i
                                class="ti ti-user-plus me-1"></i> Tambah Pelanggan</a>
                    @endif
                @endif
                @if ($user->hasRole('super admin'))
                    <a href="#" id="btnCreate" class="btn btn-primary shadow-sm"><i class="ti ti-user-plus me-1"></i>
                        Tambah Pelanggan</a>
                @endif
            @endcan
        </div>

        {{-- Info Card --}}
        <div class="card shadow-sm border mb-4">
            <div class="card-body p-4">
                <div class="row g-4 text-nowrap">
                    <div class="col-md-4 border-end">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-file-description fs-2 text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Kode Pencairan</small>
                                <h6 class="mb-0 fw-bold">{{ $pencairanprogram->kode_pencairan }}</h6>
                                <small class="text-secondary">{{ DateToIndo($pencairanprogram->tanggal) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 border-end">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-files fs-2 text-info me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Program & Cabang</small>
                                <h6 class="mb-0 fw-bold text-truncate" title="{{ $pencairanprogram->nama_program }}">
                                    {{ $pencairanprogram->nama_program }}</h6>
                                <span class="badge bg-label-info mt-1">{{ strtoupper($pencairanprogram->nama_cabang) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-calendar-event fs-2 text-warning me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Periode Penjualan</small>
                                <h6 class="mb-0 fw-bold">Semester {{ $pencairanprogram->semester }}
                                    {{ $pencairanprogram->tahun }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                 <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Daftar Pelanggan</h6>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="text-white">No.</th>
                            <th rowspan="2" class="text-white">Kode</th>
                            <th rowspan="2" class="text-white">Nama Pelanggan</th>
                            <th class="text-center text-white" colspan="3">Target</th>
                            <th class="text-center text-white" rowspan="2">Realisasi</th>
                            <th class="text-center text-white" rowspan="2">Reward</th>

                            <th rowspan="2" class="text-white">Pembayaran</th>
                            <th rowspan="2" class="text-white">No. Rekening</th>
                            <th rowspan="2" class="text-white">Pemilik</th>
                            <th rowspan="2" class="text-white">Bank</th>
                            <th rowspan="2" class="text-white"><i class="ti ti-file-description"></i></th>
                            <th rowspan="2" class="text-white"><i class="ti ti-moneybag"></i></th>
                            <th rowspan="2" class="text-white">#</th>
                        </tr>
                        <tr>
                            <th class="text-white text-center">AVG</th>
                            <th class="text-white text-center">Target</th>
                            <th class="text-white text-center">Total</th>
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
                                $total_reward =
                                    $d->total_reward > 1000000 && !in_array($d->kode_program, $bb_dep)
                                        ? 1000000
                                        : $d->total_reward;
                                $subtotal_reward += $total_reward;
                                $grandtotal_reward += $total_reward;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_pelanggan }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_rata_rata ?? 0) }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                                <td class="text-center">{{ formatAngka($d->total_target ?? 0) }}</td>

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
                                        {{-- @can('pencairanprogramikatan2026.upload')
                                            <a href="#" kode_pencairan="{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}"
                                                kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnUpload">
                                                <i class="ti ti-upload text-primary"></i>
                                            </a>
                                        @endcan --}}
                                        @can('pencairanprogramikatan2026.delete')
                                            @if ($pencairanprogram->status == '0')
                                                <form method="POST" name="deleteform" class="deleteform"
                                                    action="{{ route('pencairanprogramikatan2026.deletepelanggan', [Crypt::encrypt($pencairanprogram->kode_pencairan), Crypt::encrypt($d->kode_pelanggan)]) }}">
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
<x-modal-form id="modal" size="modal-fullscreen" show="loadmodal" title="" />
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
            $("#loadmodal").load("/pencairanprogramikatan2026/" + kode_pencairan + "/tambahpelanggan");
        });

        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            let kode_pelanggan = $(this).attr("kode_pelanggan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogramikatan2026/" + kode_pencairan + "/" +
                kode_pelanggan + "/upload");
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogramikatan2026/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });
    });
</script>
@endpush
