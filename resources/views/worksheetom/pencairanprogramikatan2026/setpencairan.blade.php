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

        {{-- Data List Cards --}}
        <div class="row" id="loaddetailpencairan">
            @php
                $metode_pembayaran = [
                    'TN' => 'Tunai',
                    'TF' => 'Transfer',
                    'VC' => 'Voucher',
                ];
                $bb_dep = ['PRIK004', 'PRIK001'];
            @endphp
            @foreach ($detail as $key => $d)
                @php
                    $total_reward =
                        $d->reward > 1000000 && !in_array($d->kode_program, $bb_dep)
                            ? 1000000
                            : $d->reward;
                @endphp
                <div class="col-12 mb-2">
                    <div class="card shadow-sm border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                {{-- 1. Pelanggan Info --}}
                                <div style="min-width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            @if (!empty($d->foto))
                                                <img src="{{ asset('storage/pelanggan/' . $d->foto) }}" alt="Avatar" class="rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/avatars/No_Image_Available.jpg') }}';">
                                            @else
                                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Avatar" class="rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary mb-1">{{ $d->kode_pelanggan }}</span>
                                            <h6 class="mb-0 fw-bold text-wrap" style="max-width: 250px;">{{ $d->nama_pelanggan }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Target Stats --}}
                                <div class="d-flex gap-3 text-center border-end pe-3">
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ formatAngka($d->avg ?? 0) }}</span>
                                        <small class="text-muted" style="font-size: 0.7rem;">AVG</small>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ formatAngka($d->target_perbulan ?? 0) }}</span>
                                        <small class="text-muted" style="font-size: 0.7rem;">TARGET</small>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-primary">{{ formatAngka(($d->avg ?? 0) + ($d->target_perbulan ?? 0)) }}</span>
                                        <small class="text-muted" style="font-size: 0.7rem;">TOTAL</small>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-info">{{ formatAngka($d->kenaikan_per_bulan) }}</span>
                                        <small class="text-muted" style="font-size: 0.7rem;">INCR</small>
                                    </div>
                                </div>

                                {{-- 3. Realisasi & Reward --}}
                                <div class="d-flex gap-4 border-end pe-3">
                                    <div class="text-center">
                                        <h6 class="mb-0 fw-bold text-warning cursor-pointer btnDetailfaktur"
                                            kode_pelanggan="{{ $d['kode_pelanggan'] }}">{{ formatAngka($d->realisasi) }}</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">REALISASI</small>
                                    </div>
                                    <div class="text-center">
                                         <h6 class="mb-0 fw-bold text-info">{{ formatAngka($d->rate) }}</h6>
                                         <small class="text-muted" style="font-size: 0.7rem;">RATE</small>
                                    </div>
                                    <div class="text-center">
                                        <h6 class="mb-0 fw-bold text-success">{{ formatAngka($total_reward) }}</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">REWARD</small>
                                    </div>
                                </div>

                                {{-- 4. Payment Info --}}
                                <div class="" style="min-width: 250px; font-size: 0.85rem;">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="ti ti-building-bank me-2 text-secondary"></i>
                                        <span class="fw-bold">{{ $d->bank }}</span>
                                        <span class="mx-1">-</span>
                                        <span>{{ $d->no_rekening }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-user me-2 text-secondary"></i>
                                        <span class="text-truncate me-2" style="max-width: 150px;"
                                            title="{{ $d->pemilik_rekening }}">{{ $d->pemilik_rekening }}</span>
                                        <span class="badge bg-label-primary">{{ $d->metode_pembayaran }}</span>
                                    </div>
                                </div>

                                {{-- 5. Actions --}}
                                <div class="d-flex align-items-center gap-2">
                                    @if (!empty($d->bukti_transfer))
                                        <a href="{{ url($d->bukti_transfer) }}" target="_blank"
                                            class="btn btn-icon btn-label-success btn-sm" title="Bukti Transfer">
                                            <i class="ti ti-receipt"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-icon btn-label-warning btn-sm" title="Belum Ada Bukti"><i
                                                class="ti ti-hourglass-empty"></i></span>
                                    @endif

                                    @can('pencairanprogramikatan2026.delete')
                                        @if ($pencairanprogram->status == '0')
                                            <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('pencairanprogramikatan2026.deletepelanggan', [Crypt::encrypt($pencairanprogram->kode_pencairan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm btn btn-icon btn-label-danger btn-sm">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
