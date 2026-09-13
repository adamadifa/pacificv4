@extends('layouts.app')
@section('titlepage', 'Pencairan Program')

@section('content')
@section('navigasi')
    <span>Pencairan Program</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_kumulatif')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pencairanprogram.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <ul class="nav nav-tabs mt-3 mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ Request('status') == '0' || Request('status') == '' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => '0']) }}">
                                <i class="ti ti-hourglass-empty me-1"></i> Pending
                                @if ($pendingCount > 0)
                                    <span class="badge rounded-pill bg-danger ms-1">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request('status') == '1' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => '1']) }}">
                                <i class="ti ti-check me-1"></i> Disetujui
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request('status') == '2' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => '2']) }}">
                                <i class="ti ti-x me-1"></i> Ditolak
                            </a>
                        </li>
                    </ul>

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pencairanprogram.index') }}">
                                <input type="hidden" name="status" value="{{ Request('status') ?? '0' }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                upperCase="true" selected="{{ Request('kode_cabang') }}" select2="select2Kodecabang" hideLabel="true" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="form-group mb-3">
                                    <x-select label="Semua Program" name="kode_program" :data="[
                                        (object)['kode' => 'PR001', 'nama' => 'BB & DP'],
                                        (object)['kode' => 'PR002', 'nama' => 'AIDA']
                                    ]" key="kode" textShow="nama" selected="{{ Request('kode_program') }}" hideLabel="true" />
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($pencairanprogram->isEmpty())
                                <div class="alert alert-warning text-center">
                                    <i class="ti ti-info-circle me-1"></i> Tidak ada data pencairan program ditemukan.
                                </div>
                            @else
                                @foreach ($pencairanprogram as $d)
                                    <div class="card mb-3 border border-light shadow-sm" style="border-radius: 8px;">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <!-- Cabang, Periode, & Tanggal -->
                                                <div class="col-lg-2 col-md-3 col-12 mb-2 mb-md-0">
                                                    <span class="badge bg-label-primary px-3 py-2 rounded text-uppercase fw-bold d-block mb-1 text-center" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                        {{ strtoupper($d->kode_cabang) }}
                                                    </span>
                                                    <div class="text-center">
                                                        <span class="badge bg-label-secondary px-2 py-1 rounded fw-semibold" style="font-size: 0.75rem;">
                                                            {{ $namabulan[$d->bulan] }} {{ $d->tahun }}
                                                        </span>
                                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                                            <i class="ti ti-calendar me-1"></i>{{ DateToIndo($d->tanggal) }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <!-- Kode Pencairan & Program -->
                                                <div class="col-lg-3 col-md-4 col-12 mb-2 mb-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2 bg-label-info p-2 rounded">
                                                            <i class="ti ti-receipt text-info fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <span class="text-dark fw-bold d-block" style="font-size: 0.9rem;">{{ $d->kode_pencairan }}</span>
                                                            <span class="badge {{ $d->kode_program == 'PR001' ? 'bg-label-info' : 'bg-label-warning' }} py-1 px-2 mt-1" style="font-size: 0.72rem;">
                                                                <i class="ti ti-tag me-1"></i>{{ $d->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Persetujuan Hirarki & Status -->
                                                <div class="col-lg-3 col-md-5 col-12 mb-2 mb-md-0">
                                                    <small class="text-muted d-block mb-1 fw-semibold" style="font-size: 0.75rem;">Status Persetujuan:</small>
                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                        <!-- OM -->
                                                        <span class="badge {{ empty($d->om) ? 'bg-label-warning' : 'bg-label-success' }} py-1 px-2" style="font-size: 0.75rem;" title="Operation Manager">
                                                            <i class="ti {{ empty($d->om) ? 'ti-hourglass-empty' : 'ti-check' }} me-1"></i>OM
                                                        </span>
                                                        <!-- RSM -->
                                                        <span class="badge {{ empty($d->rsm) ? 'bg-label-warning' : 'bg-label-success' }} py-1 px-2" style="font-size: 0.75rem;" title="Regional Sales Manager">
                                                            <i class="ti {{ empty($d->rsm) ? 'ti-hourglass-empty' : 'ti-check' }} me-1"></i>RSM
                                                        </span>
                                                        <!-- GM -->
                                                        <span class="badge {{ empty($d->gm) ? 'bg-label-warning' : 'bg-label-success' }} py-1 px-2" style="font-size: 0.75rem;" title="General Manager">
                                                            <i class="ti {{ empty($d->gm) ? 'ti-hourglass-empty' : 'ti-check' }} me-1"></i>GM
                                                        </span>
                                                        <!-- Direktur -->
                                                        <span class="badge {{ empty($d->direktur) ? 'bg-label-warning' : 'bg-label-success' }} py-1 px-2" style="font-size: 0.75rem;" title="Direktur">
                                                            <i class="ti {{ empty($d->direktur) ? 'ti-hourglass-empty' : 'ti-check' }} me-1"></i>Dir
                                                        </span>
                                                    </div>

                                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                                        <small class="text-muted fw-semibold" style="font-size: 0.75rem;">Keuangan:</small>
                                                        @if ($d->keuangan == null)
                                                            <span class="badge bg-label-warning py-1 px-2" style="font-size: 0.72rem;">
                                                                <i class="ti ti-hourglass-empty me-1"></i>Belum
                                                            </span>
                                                        @else
                                                            <span class="badge bg-label-success py-1 px-2" style="font-size: 0.72rem;">
                                                                <i class="ti ti-square-check me-1"></i>Selesai
                                                            </span>
                                                        @endif

                                                        @if (!empty($d->bukti_transfer))
                                                            <a href="{{ url($d->bukti_transfer) }}" target="_blank" class="badge bg-label-primary py-1 px-2 text-decoration-none" style="font-size: 0.72rem;">
                                                                <i class="ti ti-file-text me-1"></i>Bukti Transfer
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Status Overall & Actions -->
                                                <div class="col-lg-4 col-md-12 col-12 text-lg-end d-flex flex-wrap justify-content-lg-end justify-content-between align-items-center gap-2 mt-2 mt-lg-0">
                                                    <div>
                                                        @if ($d->status == '0')
                                                            <span class="badge bg-label-warning px-3 py-2 rounded fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="ti ti-hourglass-empty me-1"></i> Pending
                                                            </span>
                                                        @elseif ($d->status == '1')
                                                            <span class="badge bg-label-success px-3 py-2 rounded fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="ti ti-checks me-1"></i> Disetujui
                                                            </span>
                                                        @elseif ($d->status == '2')
                                                            <span class="badge bg-label-danger px-3 py-2 rounded fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="ti ti-x me-1"></i> Ditolak
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                                        @can('pencairanprogram.approve')
                                                            @if (
                                                                $user->hasRole('super admin') ||
                                                                ($user->hasRole('operation manager') && $d->rsm == null) ||
                                                                ($user->hasRole('regional sales manager') && $d->gm == null) ||
                                                                ($user->hasRole('gm marketing') && $d->direktur == null) ||
                                                                ($user->hasRole(['manager keuangan', 'staff keuangan']) && $d->status == 1) ||
                                                                ($user->hasRole('direktur') && $d->keuangan == null)
                                                            )
                                                                <a href="#" class="btn btn-sm btn-outline-success btnApprove"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}" title="Approve / Review">
                                                                    <i class="ti ti-external-link me-1"></i> Approve
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('pencairanprogram.edit')
                                                            <a href="{{ route('pencairanprogram.setpencairan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Atur Pencairan">
                                                                <i class="ti ti-settings me-1"></i> Atur
                                                            </a>
                                                        @endcan

                                                        @can('pencairanprogram.show')
                                                            <div class="btn-group">
                                                                <a href="{{ route('pencairanprogram.cetak', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                    class="btn btn-sm btn-outline-secondary" target="_blank" title="Cetak Laporan">
                                                                    <i class="ti ti-printer"></i>
                                                                </a>
                                                                <a href="{{ route('pencairanprogram.cetak', Crypt::encrypt($d->kode_pencairan)) }}?export=true"
                                                                    class="btn btn-sm btn-outline-secondary" target="_blank" title="Export Excel">
                                                                    <i class="ti ti-download"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('pencairanprogramikt.upload')
                                                            <a href="#" class="btn btn-sm btn-outline-info btnUpload"
                                                                kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}" title="Upload Bukti Transfer">
                                                                <i class="ti ti-upload"></i>
                                                            </a>
                                                        @endcan

                                                        @can('pencairanprogram.delete')
                                                            @if ($user->hasRole('operation manager') && $d->rsm == null)
                                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                                    action="{{ route('pencairanprogram.delete', Crypt::encrypt($d->kode_pencairan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-confirm" title="Hapus">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div style="float: right;">
                                {{ $pencairanprogram->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalapprove" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<x-modal-form id="modalUpload" size="" show="loadmodalupload" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Pencairan Program");
            $("#loadmodal").load("/pencairanprogram/create");
        });
        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogram/" + kode_pencairan + "/upload");
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = $(this).attr('kode_pencairan');
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogram/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".btnApprove").click(function(e) {
            const kode_pencairan = $(this).attr('kode_pencairan');
            e.preventDefault();
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Pencairan Program Ikatan");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/pencairanprogram/' + kode_pencairan + '/approve');
        });

    });
</script>
@endpush
