@extends('layouts.app')
@section('titlepage', 'Monitoring Ajuan Program Kumulatif')

@section('content')
@section('navigasi')
    <span>Monitoring Ajuan Program Kumulatif</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_kumulatif')

            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('ajuankumulatif.create')
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
                            <form action="{{ route('ajuankumulatif.index') }}">
                                <input type="hidden" name="status" value="{{ Request('status') ?? '0' }}">
                                @hasanyrole($roles_show_cabang)
                                    <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        upperCase="true" select2="select2Kodecabang" selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                                @endrole
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
                            @if ($ajuankumulatif->isEmpty())
                                <div class="alert alert-warning text-center">
                                    <i class="ti ti-info-circle me-1"></i> Tidak ada data ajuan program kumulatif ditemukan.
                                </div>
                            @else
                                @foreach ($ajuankumulatif as $d)
                                    <div class="card mb-3 border border-light shadow-sm" style="border-radius: 8px;">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <!-- Cabang & Tanggal -->
                                                <div class="col-lg-2 col-md-3 col-12 mb-2 mb-md-0">
                                                    <span class="badge bg-label-primary px-3 py-2 rounded text-uppercase fw-bold d-block mb-1 text-center" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                        {{ strtoupper($d->nama_cabang) }}
                                                    </span>
                                                    <small class="text-muted d-block text-center" style="font-size: 0.75rem;">
                                                        <i class="ti ti-calendar me-1"></i>{{ formatIndo($d->tanggal) }}
                                                    </small>
                                                </div>

                                                <!-- No Pengajuan & Dokumen -->
                                                <div class="col-lg-3 col-md-4 col-12 mb-2 mb-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2 bg-label-info p-2 rounded">
                                                            <i class="ti ti-file-description text-info fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <span class="text-dark fw-bold d-block" style="font-size: 0.9rem;">{{ $d->no_pengajuan }}</span>
                                                            @if (!empty($d->nomor_dokumen))
                                                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                                    <i class="ti ti-notes me-1"></i>{{ $d->nomor_dokumen }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Persetujuan Hirarki -->
                                                <div class="col-lg-3 col-md-5 col-12 mb-2 mb-md-0">
                                                    <small class="text-muted d-block mb-1 fw-semibold" style="font-size: 0.75rem;">Status Persetujuan:</small>
                                                    <div class="d-flex flex-wrap gap-1">
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

                                                    <div class="d-flex align-items-center gap-1">
                                                        @can('ajuankumulatif.approve')
                                                            @if (
                                                                ($user->hasRole('operation manager') && $d->rsm == null) ||
                                                                ($user->hasRole('regional sales manager') && $d->gm == null) ||
                                                                ($user->hasRole('gm marketing') && $d->direktur == null) ||
                                                                $user->hasRole(['super admin', 'direktur'])
                                                            )
                                                                <a href="#" class="btn btn-sm btn-outline-success btnApprove"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" title="Approve / Review">
                                                                    <i class="ti ti-external-link me-1"></i> Approve
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('ajuankumulatif.edit')
                                                            <a href="{{ route('ajuankumulatif.setajuankumulatif', Crypt::encrypt($d->no_pengajuan)) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Atur Ajuan">
                                                                <i class="ti ti-settings me-1"></i> Atur
                                                            </a>
                                                        @endcan

                                                        @can('ajuankumulatif.delete')
                                                            @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $d->rsm == null)
                                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                                    action="{{ route('ajuankumulatif.delete', Crypt::encrypt($d->no_pengajuan)) }}">
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
                                {{ $ajuankumulatif->links() }}
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

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program Kumulatif");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodal").load("/ajuankumulatif/create");
        });

        const select2Kodecabang = $(".select2Kodecabang");
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
            const no_pengajuan = $(this).attr('no_pengajuan');
            e.preventDefault();
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Ajuan Program Kumulatif");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/ajuankumulatif/' + no_pengajuan + '/approve');
        });
    });
</script>
@endpush
