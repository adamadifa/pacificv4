@extends('layouts.app')
@section('titlepage', 'Rekening')

@section('content')
@section('navigasi')
    <span>Rekening</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('rekening.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                                        name="nama_karyawan" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group"
                                        textShow="nama_group" selected="{{ Request('kode_group') }}" upperCase="true" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Jabatan</th>
                                        <th>MP/PCF</th>
                                        <th>Cabang</th>
                                        <th>No. Rekening</th>

                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawan as $d)
                                        <tr
                                            class="{{ $d->status_aktif_karyawan === '0' ? 'bg-danger text-white' : '' }}">
                                            <td class="text-center">
                                                {{ $loop->iteration + $karyawan->firstItem() - 1 }}
                                            </td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ textCamelCase($d->nama_karyawan) }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->kode_perusahaan == 'MP' ? 'MP' : 'PCF' }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ $d->no_rekening }}</td>


                                            <td>
                                                <div class="d-flex">
                                                    @can('rekening.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editKaryawan"
                                                                nik="{{ Crypt::encrypt($d->nik) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $karyawan->links() }}
                        </div>
                    </div>
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
