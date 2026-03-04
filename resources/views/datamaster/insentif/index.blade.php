@extends('layouts.app')
@section('titlepage', 'Insentif')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Insentif</h4>
            <small class="text-muted">Mengelola data insentif umum dan manager karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-award me-1"></i>Insentif</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('insentif.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                        name="nama_karyawan" icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                        textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group"
                        textShow="nama_group" selected="{{ Request('kode_group') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-award me-2"></i>Data Insentif</h6>
                    @can('insentif.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateInsentif">
                            <i class="ti ti-plus me-1"></i> Tambah Insentif
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
                            <th colspan="4" class="text-center text-white" style="background-color: #002e65 !important;">INSENTIF UMUM</th>
                            <th colspan="4" class="text-center text-white" style="background-color: #002e65 !important;">INSENTIF MANAGER</th>
                            <th rowspan="2" class="text-white" style="background-color: #002e65 !important;">BERLAKU</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">Masa Kerja</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Lembur</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Penempatan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KPI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Ruang Lingkup</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Penempatan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Kinerja</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Kendaraan</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($insentif as $d)
                            <tr>
                                <td>{{ $d->kode_insentif }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-semibold">{{ formatName($d->nama_karyawan) }}</span></td>
                                <td class="text-end">
                                    {{ !empty($d->iu_masakerja) ? formatRupiah($d->iu_masakerja) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->iu_lembur) ? formatRupiah($d->iu_lembur) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->iu_penempatan) ? formatRupiah($d->iu_penempatan) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->iu_kpi) ? formatRupiah($d->iu_kpi) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->im_ruanglingkup) ? formatRupiah($d->im_ruanglingkup) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->im_penempatan) ? formatRupiah($d->im_penempatan) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->im_kinerja) ? formatRupiah($d->im_kinerja) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ !empty($d->im_kendaraan) ? formatRupiah($d->im_kendaraan) : '-' }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}</td>
                                <td>
                                    @if ($d->kode_insentif == $d->kode_insentif)
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('insentif.edit')
                                                <a href="#" class="editInsentif text-primary" data-bs-toggle="tooltip"
                                                    title="Edit" kode_insentif="{{ Crypt::encrypt($d->kode_insentif) }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('insentif.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('insentif.delete', Crypt::encrypt($d->kode_insentif)) }}">
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
                    {{ $insentif->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateInsentif" size="" show="loadcreateInsentif" title="Tambah Insentif" />
<x-modal-form id="mdleditInsentif" size="" show="loadeditInsentif" title="Edit Insentif" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateInsentif").click(function(e) {
            $('#mdlcreateInsentif').modal("show");
            $("#loadcreateInsentif").load('/insentif/create');
        });

        $(".editInsentif").click(function(e) {
            var kode_insentif = $(this).attr("kode_insentif");
            e.preventDefault();
            $('#mdleditInsentif').modal("show");
            $("#loadeditInsentif").load('/insentif/' + kode_insentif + '/edit');
        });
    });
</script>
@endpush
