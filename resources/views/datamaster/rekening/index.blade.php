@extends('layouts.app')
@section('titlepage', 'Rekening')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Rekening</h4>
            <small class="text-muted">Mengelola nomor rekening karyawan untuk penggajian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-credit-card me-1"></i>Rekening</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('rekening.index') }}">
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-credit-card me-2"></i>Data Rekening</h6>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO.</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NIK</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NAMA KARYAWAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">DEPT</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JABATAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">MP/PCF</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. REKENING</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($karyawan as $d)
                            <tr class="{{ $d->status_aktif_karyawan === '0' ? 'bg-label-danger' : '' }}">
                                <td class="text-center">
                                    {{ $loop->iteration + $karyawan->firstItem() - 1 }}
                                </td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-semibold">{{ textCamelCase($d->nama_karyawan) }}</span></td>
                                <td>{{ $d->kode_dept }}</td>
                                <td>{{ $d->nama_jabatan }}</td>
                                <td>{{ $d->kode_perusahaan == 'MP' ? 'MP' : 'PCF' }}</td>
                                <td><span class="badge bg-secondary">{{ $d->kode_cabang }}</span></td>
                                <td class="fw-bold">{{ $d->no_rekening }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('rekening.edit')
                                            <a href="#" class="editKaryawan text-primary" data-bs-toggle="tooltip"
                                                title="Edit" nik="{{ Crypt::encrypt($d->nik) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $karyawan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdleditRekening" size="" show="loadeditRekening" title="Edit Rekening" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {


        $(".editKaryawan").click(function(e) {
            var nik = $(this).attr("nik");
            e.preventDefault();
            $('#mdleditRekening').modal("show");
            $("#loadeditRekening").load('/rekening/' + nik + '/edit');
        });
    });
</script>
@endpush
