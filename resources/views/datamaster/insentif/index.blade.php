@extends('layouts.app')
@section('titlepage', 'Insentif')

@section('content')
@section('navigasi')
    <span>Insentif</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('insentif.create')
                    <a href="#" class="btn btn-primary" id="btncreateInsentif"><i class="fa fa-plus me-2"></i> Tambah
                        Insentif</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('insentif.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                                        icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        selected="{{ Request('kode_cabang') }}" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                                        selected="{{ Request('kode_dept') }}" upperCase="true" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                                        selected="{{ Request('kode_group') }}" upperCase="true" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>

                                        <th rowspan="2">Kode</th>
                                        <th rowspan="2">NIK</th>
                                        <th rowspan="2" style="width: 15%">Nama Karyawan</th>
                                        <th colspan="4" class="text-center">Insentif Umum</th>
                                        <th colspan="4" class="text-center">Insentif Manager</th>
                                        <th rowspan="2">Berlaku</th>
                                        <th rowspan="2">#</th>
                                    </tr>
                                    <tr>
                                        <th>Masa Kerja</th>
                                        <th>Lembur</th>
                                        <th>Penempatan</th>
                                        <th>KPI</th>

                                        <th>Ruang Lingkup</th>
                                        <th>Penempatan</th>
                                        <th>Kinerja</th>
                                        <th>Kendaraan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($insentif as $d)
                                        <tr>

                                            <td>{{ $d->kode_insentif }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ formatName($d->nama_karyawan) }}</td>
                                            <td class="text-end">
                                                {{ !empty($d->iu_masakerja) ? formatRupiah($d->iu_masakerja) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->iu_lembur) ? formatRupiah($d->iu_lembur) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->iu_penempatan) ? formatRupiah($d->iu_penempatan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->iu_kpi) ? formatRupiah($d->iu_kpi) : '' }}
                                            </td>

                                            <td class="text-end">
                                                {{ !empty($d->im_ruanglingkup) ? formatRupiah($d->im_ruanglingkup) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->im_penempatan) ? formatRupiah($d->im_penempatan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->im_kinerja) ? formatRupiah($d->im_kinerja) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->im_kendaraan) ? formatRupiah($d->im_kendaraan) : '' }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}
                                            </td>
                                            <td>
                                                @if ($d->kode_insentif == $d->kode_insentif)
                                                    <div class="d-flex">
                                                        @can('insentif.edit')
                                                            <div>
                                                                <a href="#" class="me-2 editInsentif"
                                                                    kode_insentif="{{ Crypt::encrypt($d->kode_insentif) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('insentif.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('insentif.delete', Crypt::encrypt($d->kode_insentif)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $insentif->links() }}
                        </div>
                    </div>
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
