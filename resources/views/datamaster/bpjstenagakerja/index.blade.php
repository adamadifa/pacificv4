@extends('layouts.app')
@section('titlepage', 'BPJS Tenaga Kerja')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">BPJS Tenaga Kerja</h4>
            <small class="text-muted">Mengelola data iuran BPJS Tenaga Kerja karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-shield-check me-1"></i>BPJS Tenaga Kerja</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('bpjstenagakerja.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                        icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        selected="{{ Request('kode_dept') }}" upperCase="true" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                        selected="{{ Request('kode_group') }}" upperCase="true" hideLabel="true" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-shield-check me-2"></i>Data BPJS Tenaga Kerja</h6>
                    @can('bpjstenagakerja.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateBpjstenagakerja">
                            <i class="ti ti-plus me-1"></i> Tambah BPJS Tenaga Kerja
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NIK</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NAMA KARYAWAN</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">IURAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">BERLAKU</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($bpjstenagakerja as $d)
                            <tr>
                                <td>{{ $d->kode_bpjs_tenagakerja }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-semibold">{{ textCamelCase($d->nama_karyawan) }}</span></td>
                                <td class="text-end fw-bold">
                                    {{ !empty($d->iuran) ? formatRupiah($d->iuran) : '-' }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}</td>
                                <td>
                                    @if ($d->kode_bpjs_tenagakerja == $d->kode_lastbpjstenagakerja)
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('bpjstenagakerja.edit')
                                                <a href="#" class="editBpjstenagakerja text-primary" data-bs-toggle="tooltip"
                                                    title="Edit"
                                                    kode_bpjs_tenagakerja="{{ Crypt::encrypt($d->kode_bpjs_tenagakerja) }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('bpjstenagakerja.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('bpjstenagakerja.delete', Crypt::encrypt($d->kode_bpjs_tenagakerja)) }}">
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
                    {{ $bpjstenagakerja->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBpjstenagakerja" size="" show="loadcreateBpjstenagakerja" title="Tambah BPJS Tenaga Kerja" />
<x-modal-form id="mdleditBpjstenagakerja" size="" show="loadeditBpjstenagakerja" title="Edit BPJS Tenaga Kerja" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateBpjstenagakerja").click(function(e) {
            $('#mdlcreateBpjstenagakerja').modal("show");
            $("#loadcreateBpjstenagakerja").load('/bpjstenagakerja/create');
        });

        $(".editBpjstenagakerja").click(function(e) {
            var kode_bpjs_tenagakerja = $(this).attr("kode_bpjs_tenagakerja");
            e.preventDefault();
            $('#mdleditBpjstenagakerja').modal("show");
            $("#loadeditBpjstenagakerja").load('/bpjstenagakerja/' + kode_bpjs_tenagakerja + '/edit');
        });
    });
</script>
@endpush
