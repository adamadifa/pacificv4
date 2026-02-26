@extends('layouts.app')
@section('titlepage', 'Karyawan')

@section('content')
@section('navigasi')
    <span>Karyawan</span>
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
            @hasanyrole($roles_show_cabang)
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" selected="{{ Request('kode_cabang_search') }}" upperCase="true"
                            select2="select2Kodecabangsearch" />
                    </div>
                </div>
            @endhasanyrole
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                        name="nama_karyawan" icon="ti ti-search" />
                </div>

                <div class="col-lg-4 col-sm-12 col-md-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true"
                        select2="select2Kodedeptsearch" />
                </div>
                <div class="col-lg-2 col-sm-12 col-md-12">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                        selected="{{ Request('kode_group') }}" upperCase="true" />
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
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
            <div class="card shadow-none border {{ $d->status_aktif_karyawan === '0' ? 'bg-label-danger border-danger' : '' }}">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <!-- Bagian 1: Identitas (Left) -->
                        <div class="col-md-5 border-end">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-lg">
                                        @if (!empty($d->foto))
                                            @if (Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar"
                                                    class="rounded-circle">
                                            @else
                                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                alt="Avatar" class="rounded-circle">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">
                                        {!! textCamelCase($d->nama_karyawan) !!}
                                        <span class="text-muted" style="font-size: 0.75rem;">({{ $d->nik }})</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge border text-primary bg-label-primary"
                                            style="font-size: 0.65rem;">{{ $d->nama_jabatan }}</span>
                                        <span class="badge border text-info bg-label-info"
                                            style="font-size: 0.65rem;">{{ $d->nama_dept }}</span>
                                        <span class="badge border text-warning bg-label-warning"
                                            style="font-size: 0.65rem;">{{ $d->kode_cabang }}</span>
                                        @if ($d->status_karyawan == 'T')
                                            <span class="badge border text-success bg-label-success"
                                                style="font-size: 0.65rem;">Tetap</span>
                                        @elseif ($d->status_karyawan == 'K')
                                            <span class="badge border text-danger bg-label-danger"
                                                style="font-size: 0.65rem;">Kontrak</span>
                                        @else
                                            <span class="badge border text-secondary bg-label-secondary"
                                                style="font-size: 0.65rem;">Outsource</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Status & Masa Kerja (Center) -->
                        <div class="col-md-3 border-end text-center d-flex flex-column align-items-center justify-content-center">
                            @if ($d->status_aktif_karyawan == 1)
                                <span class="badge bg-success mb-2 px-3">Aktif</span>
                            @else
                                <span class="badge bg-danger mb-2 px-3">Non-Aktif</span>
                            @endif
                            <div class="text-muted" style="font-size: 0.75rem;">Masuk: {{ date('d-m-Y', strtotime($d->tanggal_masuk)) }}</div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $masa_kerja }}</div>
                        </div>

                        <!-- Bagian 3: Security & Actions (Right) -->
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex gap-4 ms-3">
                                    <div class="text-center">
                                        @if ($d->lock_location == 1)
                                            <a href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}"
                                                class="text-success d-block mb-1">
                                                <i class="ti ti-lock-open ti-md"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}"
                                                class="text-danger d-block mb-1">
                                                <i class="ti ti-lock ti-md"></i>
                                            </a>
                                        @endif
                                        <span class="text-muted" style="font-size: 0.65rem;">Location</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end h-100 justify-content-center">
                                    <div class="btn-group">
                                        @can('karyawan.edit')
                                            <a href="#" class="btn btn-icon btn-outline-primary editKaryawan"
                                                nik="{{ Crypt::encrypt($d->nik) }}" title="Edit">
                                                <i class="ti ti-edit text-primary"></i>
                                            </a>
                                        @endcan
                                        @can('karyawan.show')
                                            <a href="{{ route('karyawan.show', Crypt::encrypt($d->nik)) }}"
                                                class="btn btn-icon btn-outline-info"
                                                style="margin-left: -1px; border-radius: 0 !important;" title="Detail">
                                                <i class="ti ti-file-description text-info"></i>
                                            </a>
                                        @endcan
                                        @can('karyawan.delete')
                                            <form method="POST" name="deleteform" class="deleteform m-0"
                                                style="margin-left: -1px;"
                                                action="{{ route('karyawan.delete', Crypt::encrypt($d->nik)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-icon btn-outline-danger delete-confirm"
                                                    style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"
                                                    title="Delete">
                                                    <i class="ti ti-trash text-danger"></i>
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
