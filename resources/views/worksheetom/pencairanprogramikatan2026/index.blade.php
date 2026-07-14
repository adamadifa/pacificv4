@extends('layouts.app')
@section('titlepage', 'Pencairan Program Marketing 2026')

@section('content')
@section('navigasi')
    <span>Pencairan Program Marketing 2026</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_marketing_2026')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pencairanprogramikatan2026.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Buat Pencairan</a>
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
                            <form action="{{ route('pencairanprogramikatan2026.index') }}">
                                <input type="hidden" name="status" value="{{ Request('status') ?? '0' }}">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                </div>
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
                        @foreach ($pencairanprogramikatan as $d)
                            <div class="col-12 mb-3">
                                <div class="card card-hover shadow-sm border" style="transition: all 0.2s ease-in-out;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center gx-3">
                                            {{-- Kode Pencairan & Tanggal --}}
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-primary fs-5">#{{ $d->kode_pencairan }}</span>
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
                                                    <div class="d-flex align-items-center mt-1 text-muted d-md-none">
                                                        <i class="ti ti-calendar-event me-1" style="font-size: 0.8rem;"></i>
                                                        <small>Semester {{ $d->semester }} {{ $d->tahun }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Periode (Bulan/Tahun) --}}
                                            <div class="col-xl-2 col-lg-2 d-none d-lg-block mb-2 mb-md-0">
                                                <div class="d-flex align-items-center text-secondary bg-label-secondary px-2 py-1 rounded" style="width: fit-content;">
                                                    <i class="ti ti-calendar-event me-2"></i>
                                                    <small class="fw-bold text-nowrap">
                                                       Semester {{ $d->semester }} {{ $d->tahun }}
                                                    </small>
                                                </div>
                                            </div>
                                             {{-- Status --}}
                                             <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12 mb-2 mb-md-0">
                                                 <div class="d-flex justify-content-start align-items-center gap-3">
                                                     {{-- OM --}}
                                                     <div class="text-center position-relative">
                                                         <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">OM</small>
                                                         @if (empty($d->om)) 
                                                             <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty" style="font-size: 0.8rem;"></i></span></div>
                                                         @else 
                                                             <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check" style="font-size: 0.8rem;"></i></span></div>
                                                         @endif
                                                     </div>
                                                     {{-- RSM --}}
                                                     <div class="text-center position-relative">
                                                         <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">RSM</small>
                                                         @if (empty($d->rsm)) 
                                                             <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty" style="font-size: 0.8rem;"></i></span></div>
                                                         @else 
                                                             @if (empty($d->gm) && $d->status == '2') 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x" style="font-size: 0.8rem;"></i></span></div>
                                                             @else 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check" style="font-size: 0.8rem;"></i></span></div>
                                                             @endif
                                                         @endif
                                                     </div>
                                                     {{-- GM --}}
                                                     <div class="text-center position-relative">
                                                         <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">GM</small>
                                                         @if (empty($d->gm)) 
                                                              <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty" style="font-size: 0.8rem;"></i></span></div>
                                                         @else
                                                             @if (empty($d->direktur) && $d->status == '2') 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x" style="font-size: 0.8rem;"></i></span></div>
                                                             @else 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check" style="font-size: 0.8rem;"></i></span></div>
                                                             @endif
                                                         @endif
                                                     </div>
                                                     {{-- DIR --}}
                                                     <div class="text-center position-relative">
                                                         <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">DIR</small>
                                                         @if (empty($d->direktur)) 
                                                              <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty" style="font-size: 0.8rem;"></i></span></div>
                                                         @else
                                                             @if ($d->status == '2') 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x" style="font-size: 0.8rem;"></i></span></div>
                                                             @else 
                                                                 <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check" style="font-size: 0.8rem;"></i></span></div>
                                                             @endif
                                                         @endif
                                                     </div>
                                                     {{-- KEU --}}
                                                     <div class="text-center position-relative">
                                                         <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">KEU</small>
                                                         @if (empty($d->keuangan)) 
                                                              <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty" style="font-size: 0.8rem;"></i></span></div>
                                                         @else
                                                              <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check" style="font-size: 0.8rem;"></i></span></div>
                                                         @endif
                                                     </div>
                                                 </div>
                                             </div>


                                            {{-- Actions --}}
                                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mt-2 mt-lg-0">
                                                <div class="d-flex justify-content-lg-end justify-content-start gap-1">
                                                    <a href="#" class="btn btn-icon btn-label-info btnShow me-1"
                                                        kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}" data-bs-toggle="tooltip" title="Detail">
                                                        <i class="ti ti-file-description"></i>
                                                    </a>
                                                    <a href="{{ route('pencairanprogramikatan2026.cetak', Crypt::encrypt($d->kode_pencairan)) }}?export=true"
                                                        class="btn btn-icon btn-label-success me-1" data-bs-toggle="tooltip" title="Export Excel" target="_blank">
                                                        <i class="ti ti-file-spreadsheet"></i>
                                                    </a>
                                                    @if ($user->hasRole('super admin') || $user->can('ajuanprogramikatan.approve'))
                                                        @if (($user->hasRole('operation manager') && $d->rsm == null) ||
                                                            ($user->hasRole('regional sales manager') && $d->gm == null) ||
                                                            ($user->hasRole('gm marketing') && $d->direktur == null) ||
                                                            ($user->hasRole(['manager keuangan', 'staff keuangan']) && $d->status == 1) ||
                                                            ($user->hasRole(['super admin', 'direktur'])))
                                                            <a href="#" class="btn btn-icon btn-label-success btnApprove me-1"
                                                                kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}" data-bs-toggle="tooltip" title="Approve">
                                                                <i class="ti ti-external-link"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    @can('pencairanprogramikatan2026.edit')
                                                        <a href="{{ route('pencairanprogramikatan2026.setpencairan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                            class="btn btn-icon btn-label-primary me-1" data-bs-toggle="tooltip" title="Set Pencairan">
                                                            <i class="ti ti-settings"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pencairanprogramikatan2026.delete')
                                                       @if ($d->status == '0')
                                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                                action="{{ route('pencairanprogramikatan2026.delete', Crypt::encrypt($d->kode_pencairan)) }}">
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
                             {{ $pencairanprogramikatan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-fullscreen" show="loadmodalapprove" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Pencairan Program Ikatan");
            $("#loadmodal").load("/pencairanprogramikatan2026/create");
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            var kode_pencairan = $(this).attr("kode_pencairan");
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Pencairan Program Ikatan");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/pencairanprogramikatan2026/' + kode_pencairan + '/approve');
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var kode_pencairan = $(this).attr("kode_pencairan");
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Detail Pencairan Program Ikatan");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/pencairanprogramikatan2026/' + kode_pencairan + '/show');
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
    });
</script>
@endpush
