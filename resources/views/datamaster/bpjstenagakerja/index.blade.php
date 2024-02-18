@extends('layouts.app')
@section('titlepage', 'BPJS Tenaga Kerja')

@section('content')
@section('navigasi')
    <span>BPJS Tenaga Kerja</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpjstenagakerja.create')
                    <a href="#" class="btn btn-primary" id="btncreateBpjsTenagaKerja"><i class="fa fa-plus me-2"></i>
                        Tambah
                        BPJS Tenaga Kerja</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('bpjstenagakerja.index') }}">
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
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>

                                        <th>Kode</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Iuran</th>
                                        <th>Berlaku</th>
                                        <th>#</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($bpjstenagakerja as $d)
                                        <tr>
                                            <td>{{ $d->kode_bpjs_tenagakerja }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ textCamelCase($d->nama_karyawan) }}</td>
                                            <td class="text-end">
                                                {{ !empty($d->iuran) ? formatRupiah($d->iuran) : '' }}
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}</td>
                                            <td>
                                                @if ($d->kode_bpjs_tenagakerja == $d->kode_lastbpjstenagakerja)
                                                    <div class="d-flex">
                                                        @can('bpjstenagakerja.edit')
                                                            <div>
                                                                <a href="#" class="me-2 editBpjsTenagaKerja"
                                                                    kode_bpjs_tenagakerja="{{ Crypt::encrypt($d->kode_bpjs_tenagakerja) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('bpjstenagakerja.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('bpjstenagakerja.delete', Crypt::encrypt($d->kode_bpjs_tenagakerja)) }}">
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
                            {{ $bpjstenagakerja->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBpjsTenagaKerja" size="" show="loadcreateBpjsTenagaKerja"
    title="Tambah BPJS Tenaga Kerja" />
<x-modal-form id="mdleditBpjsTenagaKerja" size="" show="loadeditBpjsTenagaKerja"
    title="Edit BPJS Tenaga Kerja" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateBpjsTenagaKerja").click(function(e) {
            $('#mdlcreateBpjsTenagaKerja').modal("show");
            $("#loadcreateBpjsTenagaKerja").load('/bpjstenagakerja/create');
        });

        $(".editBpjsTenagaKerja").click(function(e) {
            var kode_bpjs_tenagakerja = $(this).attr("kode_bpjs_tenagakerja");
            e.preventDefault();
            $('#mdleditBpjsTenagaKerja').modal("show");
            $("#loadeditBpjsTenagaKerja").load('/bpjstenagakerja/' + kode_bpjs_tenagakerja + '/edit');
        });
    });
</script>
@endpush
