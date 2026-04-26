@extends('layouts.app')
@section('titlepage', 'Karyawan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Karyawan</h4>
            <small class="text-muted">Mengelola data karyawan, jabatan, dan departemen.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Karyawan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-2">
    <div class="col-12">
        @can('karyawan.create')
            <a href="#" class="btn btn-primary" id="btncreateKaryawan"><i class="fa fa-plus me-2"></i> Tambah
                Karyawan</a>
        @endcan
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <form action="{{ route('karyawan.index') }}">
            <div class="row g-2 align-items-end">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" selected="{{ Request('kode_cabang_search') }}" upperCase="true"
                            select2="select2Kodecabangsearch" hideLabel="true" />
                    </div>
                @endhasanyrole
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                        name="nama_karyawan" icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true"
                        select2="select2Kodedeptsearch" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                        selected="{{ Request('kode_group') }}" upperCase="true" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @foreach ($karyawan as $d)
        @php
            $tgl_masuk = \Carbon\Carbon::parse($d->tanggal_masuk);
            $diff = $tgl_masuk->diff(\Carbon\Carbon::now());
            $masa_kerja = $diff->y . ' Th ' . $diff->m . ' Bln';
        @endphp
        <div class="col-12 mb-3">
            <div class="card shadow-none border {{ $d->status_aktif_karyawan === '0' ? 'bg-label-danger border-danger' : 'border-light' }}">
                <div class="card-body p-3">
                    <div class="row align-items-center g-3">
                        <!-- Left Section: Avatar & Basic Info -->
                        <div class="col-lg-4 col-md-12 border-end-lg person-info">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <div class="avatar avatar-xl online">
                                        @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . $d->getRawOriginal('foto')))
                                            <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="rounded shadow-sm">
                                        @else
                                            <div class="avatar-initial rounded bg-label-secondary border">
                                                <i class="ti ti-user ti-md"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="badge badge-dot {{ $d->status_aktif_karyawan == 1 ? 'bg-success' : 'bg-danger' }} position-absolute bottom-0 end-0 border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{!! textCamelCase($d->nama_karyawan) !!}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small"><i class="ti ti-id me-1"></i>{{ $d->nik }}</span>
                                        <span class="badge {{ $d->status_aktif_karyawan == 1 ? 'bg-label-success' : 'bg-label-danger' }} py-0 px-2" style="font-size: 0.65rem">
                                            {{ $d->status_aktif_karyawan == 1 ? 'Aktif' : 'Off' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Section: Position & Dept -->
                        <div class="col-lg-5 col-md-12 border-end-lg">
                            <div class="row align-items-center">
                                <div class="col-sm-7 border-end-sm">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center text-dark fw-semibold" style="font-size: 0.85rem;">
                                            <i class="ti ti-briefcase me-2 text-primary" style="font-size: 1rem;"></i>
                                            {{ $d->nama_jabatan }}
                                        </div>
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="ti ti-hierarchy-2 me-2" style="font-size: 1rem;"></i>
                                            {{ $d->nama_dept }}
                                        </div>
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="ti ti-map-pin me-2 text-warning" style="font-size: 1rem;"></i>
                                            {{ $d->kode_cabang }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5 ps-sm-3 mt-3 mt-sm-0">
                                    <div class="text-muted mb-1" style="font-size: 0.7rem;">Masa Kerja & Status</div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">{{ $masa_kerja }}</div>
                                    @php
                                        $statusClass = [
                                            'T' => 'bg-label-success',
                                            'K' => 'bg-label-warning',
                                            'default' => 'bg-label-secondary'
                                        ];
                                        $statusText = ['T' => 'Tetap', 'K' => 'Kontrak', 'default' => 'Outsource'];
                                        $currentStatus = $d->status_karyawan;
                                        $btnClass = $statusClass[$currentStatus] ?? $statusClass['default'];
                                        $btnText = $statusText[$currentStatus] ?? $statusText['default'];
                                    @endphp
                                    <span class="badge border {{ $btnClass }}" style="font-size: 0.65rem;">{{ $btnText }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Security Controls & Actions -->
                        <div class="col-lg-3 col-md-12">
                            <div class="d-flex align-items-center justify-content-lg-end justify-content-between">
                                <div class="text-center me-lg-4">
                                    <a href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}"
                                        class="{{ $d->lock_location == 1 ? 'text-success' : 'text-danger' }} d-inline-block hover-scale"
                                        title="{{ $d->lock_location == 1 ? 'Location Unlocked' : 'Location Locked' }}">
                                        @if ($d->lock_location == 1)
                                            <i class="ti ti-lock-open ti-md"></i>
                                        @else
                                            <i class="ti ti-lock ti-md"></i>
                                        @endif
                                    </a>
                                    <div class="text-muted" style="font-size: 0.6rem;">Lock Loc</div>
                                </div>

                                <div class="btn-group shadow-sm rounded">
                                    @can('karyawan.edit')
                                        <a href="#" class="btn btn-icon btn-outline-primary editKaryawan" nik="{{ Crypt::encrypt($d->nik) }}" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('karyawan.show')
                                        <a href="{{ route('karyawan.show', Crypt::encrypt($d->nik)) }}" class="btn btn-icon btn-outline-info" title="Detail">
                                            <i class="ti ti-file-description"></i>
                                        </a>
                                    @endcan
                                    @can('karyawan.delete')
                                        <form method="POST" name="deleteform" class="deleteform m-0" action="{{ route('karyawan.delete', Crypt::encrypt($d->nik)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-outline-danger delete-confirm rounded-0 rounded-end" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="row">
    <div class="col-12">
        <div style="float: right;">
            {{ $karyawan->links() }}
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateKaryawan" size="" show="loadcreateKaryawan" title="Tambah Karyawan" />
<x-modal-form id="mdleditKaryawan" size="" show="loadeditKaryawan" title="Edit Karyawan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateKaryawan").click(function(e) {
            $('#mdlcreateKaryawan').modal("show");
            $("#loadcreateKaryawan").load('/karyawan/create');
        });

        $(".editKaryawan").click(function(e) {
            var nik = $(this).attr("nik");
            e.preventDefault();
            $('#mdleditKaryawan').modal("show");
            $("#loadeditKaryawan").load('/karyawan/' + nik + '/edit');
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodedeptsearch = $('.select2Kodedeptsearch');
        if (select2Kodedeptsearch.length) {
            select2Kodedeptsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
