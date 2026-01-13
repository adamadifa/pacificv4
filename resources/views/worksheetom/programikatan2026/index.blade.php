@extends('layouts.app')
@section('titlepage', 'Monitoring Program Ikatan 2026')

@section('content')
@section('navigasi')
    <span>Monitoring Program Ikatan 2026</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_marketing_2026')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('programikatan2026.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('programikatan2026.index') }}">
                                @hasanyrole($roles_access_all_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                {{-- <x-input-with-icon label="No. Dokumen" value="{{ Request('nomor_dokumen') }}" name="nomor_dokumen"
                                    icon="ti ti-barcode" /> --}}
                                <x-select label="Semua Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program"
                                    select2="select2Kodeprogram" upperCase="true" selected="{{ Request('kode_program') }}" />
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ Request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ Request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
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
                        @foreach ($ajuanprogramikatan as $d)
                            <div class="col-12 mb-3">
                                <div class="card card-hover shadow-sm border" style="transition: all 0.2s ease-in-out;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center gx-3">
                                            {{-- ID & Tanggal (Expanded to col-2) --}}
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-primary fs-5">#{{ $d->no_pengajuan }}</span>
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="ti ti-calendar me-1" style="font-size: 0.8rem;"></i>
                                                        <small class="text-nowrap">{{ formatIndo($d->tanggal) }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Program & Cabang --}}
                                            <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark text-truncate" style="font-size: 1rem;" title="{{ $d->nama_program }}">{{ $d->nama_program }}</span>
                                                    <small class="text-secondary text-uppercase fw-semibold">{{ $d->nama_cabang }}</small>
                                                    <div class="d-flex align-items-center mt-1 text-muted d-md-none flex-wrap gap-1">
                                                         {{-- Mobile only period check --}}
                                                        <div class="d-flex align-items-center">
                                                            <i class="ti ti-clock me-1" style="font-size: 0.8rem;"></i>
                                                            <small>{{ date('m/y', strtotime($d->periode_dari)) }} - {{ date('m/y', strtotime($d->periode_sampai)) }}</small>
                                                        </div>
                                                        @if(!empty($d->semester))
                                                            <small class="badge bg-label-info p-1" style="font-size: 0.7rem;">Sem {{ $d->semester }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Periode (Hidden on very small, visible on lg) --}}
                                            <div class="col-xl-2 col-lg-2 d-none d-lg-block mb-2 mb-md-0">
                                                <div class="d-flex align-items-center text-secondary bg-label-secondary px-2 py-1 rounded" style="width: fit-content;">
                                                    <i class="ti ti-clock me-2"></i>
                                                    <small class="fw-bold text-nowrap">
                                                        {{ date('m/y', strtotime($d->periode_dari)) }} - {{ date('m/y', strtotime($d->periode_sampai)) }}
                                                    </small>
                                                </div>
                                                @if(!empty($d->semester))
                                                    <span class="badge bg-label-info mt-1" style="width: fit-content;">Semester {{ $d->semester }}</span>
                                                @endif
                                            </div>

                                            {{-- Approval (Expanded to col-3) --}}
                                            <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex justify-content-start align-items-center gap-3">
                                                    {{-- OM --}}
                                                    <div class="text-center position-relative">
                                                        <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">OM</small>
                                                        @if (empty($d->om)) 
                                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                                        @else 
                                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                                        @endif
                                                    </div>
                                                    {{-- RSM --}}
                                                    <div class="text-center position-relative">
                                                        <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">RSM</small>
                                                        @if (empty($d->rsm)) 
                                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                                        @else 
                                                            @if (empty($d->gm) && $d->status == '2') 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                                            @else 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    {{-- GM --}}
                                                    <div class="text-center position-relative">
                                                        <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">GM</small>
                                                        @if (empty($d->gm)) 
                                                             <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                                        @else
                                                            @if (empty($d->direktur) && $d->status == '2') 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                                            @else 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    {{-- DIR --}}
                                                    <div class="text-center position-relative">
                                                        <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">DIR</small>
                                                        @if (empty($d->direktur)) 
                                                             <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                                        @else
                                                            @if ($d->status == '2') 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                                            @else 
                                                                <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    {{-- Status Badge Inline --}}
                                                     <div class="ms-2 border-start ps-3 d-flex align-items-center">
                                                        @if ($d->status == '0')
                                                            <span class="badge bg-label-warning text-warning d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-hourglass-empty fs-6"></i> Pending</span>
                                                        @elseif ($d->status == '1')
                                                            <span class="badge bg-success d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-check fs-6"></i> Disetujui</span>
                                                        @elseif($d->status == '2')
                                                            <span class="badge bg-danger d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-ban fs-6"></i> Ditolak</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Actions --}}
                                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mt-2 mt-lg-0">
                                                <div class="d-flex justify-content-lg-end justify-content-start gap-1">
                                                    @can('programikatan2026.approve')
                                                        @if (($user->hasRole('operation manager') && $d->rsm == null) ||
                                                            ($user->hasRole('regional sales manager') && $d->gm == null) ||
                                                            ($user->hasRole('gm marketing') && $d->direktur == null) ||
                                                            ($user->hasRole(['super admin', 'direktur'])))
                                                            <a href="#" class="btn btn-icon btn-label-success btnApprove"
                                                                no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip" title="Approve">
                                                                <i class="ti ti-external-link"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    
                                                    @can('programikatan2026.show')
                                                        <a href="{{ route('programikatan2026.cetak', Crypt::encrypt($d->no_pengajuan)) }}"
                                                            target="_blank" class="btn btn-icon btn-label-secondary" data-bs-toggle="tooltip" title="Cetak">
                                                            <i class="ti ti-printer"></i>
                                                        </a>
                                                    @endcan

                                                    @can('programikatan2026.edit')
                                                        <a href="{{ route('programikatan2026.setajuanprogramikatan', Crypt::encrypt($d->no_pengajuan)) }}"
                                                            class="btn btn-icon btn-label-primary" data-bs-toggle="tooltip" title="Atur">
                                                            <i class="ti ti-settings"></i>
                                                        </a>
                                                    @endcan

                                                    @can('programikatan2026.delete')
                                                        @if ($user->hasRole(['operation manager', 'sales marketing manager','super admin']) && $d->rsm == null)
                                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                                action="{{ route('programikatan2026.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-icon btn-label-danger delete-confirm" data-bs-toggle="tooltip" title="Hapus">
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
                            </div>
                        @endforeach
                        <div class="col-12 mt-4">
                             {{ $ajuanprogramikatan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xxl" show="loadmodalapprove" title="" />
<x-modal-form id="modalDetailtarget" size="" show="loadmodaldetailtarget" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodal").load("/programikatan2026/create");
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
            $("#modalApprove").find(".modal-title").text("Approve Ajuan Program");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/programikatan2026/' + no_pengajuan + '/approve');
        });



        $(document).on('click', '.btnDetailTarget', function(e) {
            e.preventDefault();
            let no_pengajuan = $(this).attr('no_pengajuan');
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            $("#modalDetailtarget").modal("show");
            $("#modalDetailtarget").find(".modal-title").text('Detail Target Pelanggan');
            $("#modalDetailtarget").find("#loadmodaldetailtarget").load(
                `/programikatan2026/${no_pengajuan}/${kode_pelanggan}/detailtarget`);
        });
    });
</script>
@endpush
