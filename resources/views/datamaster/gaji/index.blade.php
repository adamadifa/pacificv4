@extends('layouts.app')
@section('titlepage', 'Gaji')

@section('content')
@section('navigasi')
    <span>Gaji</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('gaji.create')
                    <a href="#" class="btn btn-primary" id="btncreateGaji"><i class="fa fa-plus me-2"></i> Tambah
                        Gaji</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('gaji.index') }}">
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
                                        <th rowspan="2">Gaji Pokok</th>
                                        <th colspan="6" class="text-center">Tunjangan</th>
                                        <th rowspan="2">Berlaku</th>
                                        <th rowspan="2">#</th>
                                    </tr>
                                    <tr>
                                        <th>Jabatan</th>
                                        <th>Masa Kerja</th>
                                        <th>Tang. Jawab</th>
                                        <th>Makan</th>
                                        <th>Istri</th>
                                        <th>Skill</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gaji as $d)
                                        <tr>

                                            <td>{{ $d->kode_gaji }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ textCamelCase($d->nama_karyawan) }}</td>
                                            <td class="text-end">{{ formatRupiah($d->gaji_pokok) }}</td>
                                            <td class="text-end">
                                                {{ !empty($d->t_jabatan) ? formatRupiah($d->t_jabatan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->t_masakerja) ? formatRupiah($d->t_masakerja) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->t_tanggungjawab) ? formatRupiah($d->t_tanggungjawab) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->t_makan) ? formatRupiah($d->t_makan) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->t_istri) ? formatRupiah($d->t_istri) : '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ !empty($d->t_skill) ? formatRupiah($d->t_skill) : '' }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}
                                            </td>
                                            <td>
                                                @if ($d->kode_gaji == $d->kode_lastgaji)
                                                    <div class="d-flex">
                                                        @can('gaji.edit')
                                                            <div>
                                                                <a href="#" class="me-2 editGaji" kode_gaji="{{ Crypt::encrypt($d->kode_gaji) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('gaji.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('gaji.delete', Crypt::encrypt($d->kode_gaji)) }}">
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
                            {{ $gaji->links() }}
                        </div>
                    </div>
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
