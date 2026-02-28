@extends('layouts.app')
@section('titlepage', 'Gaji')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gaji</h4>
            <small class="text-muted">Mengelola data gaji pokok dan tunjangan karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-wallet me-1"></i>Gaji</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('gaji.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                        icon="ti ti-search" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_cabang') }}" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        selected="{{ Request('kode_dept') }}" upperCase="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                        selected="{{ Request('kode_group') }}" upperCase="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Data Gaji</h6>
                    @can('gaji.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateGaji">
                            <i class="ti ti-plus me-1"></i> Tambah Gaji
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">NIK</th>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">NAMA KARYAWAN</th>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">GAJI POKOK</th>
                            <th colspan="6" class="text-center text-white" style="background-color: #002e65 !important;">TUNJANGAN</th>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">BERLAKU</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">Jabatan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Masa Kerja</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Tang. Jawab</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Makan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Istri</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Skill</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($gaji as $d)
                            <tr>
                                <td>{{ $d->kode_gaji }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-semibold">{{ textCamelCase($d->nama_karyawan) }}</span></td>
                                <td class="text-end fw-bold">{{ formatRupiah($d->gaji_pokok) }}</td>
                                <td class="text-end">
                                    {{ !empty($d->t_jabatan) ? formatRupiah($d->t_jabatan) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->t_masakerja) ? formatRupiah($d->t_masakerja) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->t_tanggungjawab) ? formatRupiah($d->t_tanggungjawab) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->t_makan) ? formatRupiah($d->t_makan) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->t_istri) ? formatRupiah($d->t_istri) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->t_skill) ? formatRupiah($d->t_skill) : '-' }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}</td>
                                <td>
                                    @if ($d->kode_gaji == $d->kode_lastgaji)
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('gaji.edit')
                                                <a href="#" class="editGaji text-primary" data-bs-toggle="tooltip"
                                                    title="Edit" kode_gaji="{{ Crypt::encrypt($d->kode_gaji) }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('gaji.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('gaji.delete', Crypt::encrypt($d->kode_gaji)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $gaji->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateGaji" size="" show="loadcreateGaji" title="Tambah Gaji" />
<x-modal-form id="mdleditGaji" size="" show="loadeditGaji" title="Edit Gaji" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateGaji").click(function(e) {
            $('#mdlcreateGaji').modal("show");
            $("#loadcreateGaji").load('/gaji/create');
        });

        $(".editGaji").click(function(e) {
            var kode_gaji = $(this).attr("kode_gaji");
            e.preventDefault();
            $('#mdleditGaji').modal("show");
            $("#loadeditGaji").load('/gaji/' + kode_gaji + '/edit');
        });
    });
</script>
@endpush
