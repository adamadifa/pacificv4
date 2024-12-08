@extends('layouts.app')
@section('titlepage', 'Pencairan Program')

@section('content')
@section('navigasi')
    <span>Pencairan Program</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('barangmasukgl.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('monitoringprogram.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                upperCase="true" selected="{{ Request('kode_cabang') }}" select2="select2Kodecabang" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
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
                                            <th rowspan="2" valign="middle">No. Ajuan</th>
                                            <th rowspan="2" valign="middle">Tanggal</th>
                                            <th rowspan="2" valign="middle">Bulan</th>
                                            <th rowspan="2" valign="middle">Tahun</th>
                                            <th rowspan="2" valign="middle">No. Dok</th>
                                            <th rowspan="2" valign="middle">Program</th>
                                            <th rowspan="2" valign="middle">Cabang</th>
                                            <th rowspan="2" valign="middle">Periode</th>
                                            <th colspan="3" class="text-center">Persetujuan</th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">RSM</th>
                                            <th class="text-center">GM</th>
                                            <th class="text-center">Direktur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pencairanprogramikatan as $d)
                                            <tr>
                                                <td>{{ $d->kode_pencairan }}</td>
                                                <td>{{ $d->tanggal }}</td>
                                                <td>{{ $namabulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ $d->nomor_dokumen }}</td>
                                                <td>{{ $d->nama_program }}</td>
                                                <td>{{ strtoUpper($d->nama_cabang) }}</td>
                                                <td>{{ formatIndo($d->periode_dari) }} - {{ formatIndo($d->periode_sampai) }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('pencairanprogramikatan.setpencairan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                            class="me-1">
                                                            <i class="ti ti-settings text-primary"></i>
                                                        </a>

                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('pencairanprogramikatan.delete', Crypt::encrypt($d->kode_pencairan)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{-- {{ $barangmasuk->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalajuanProgram" size="modal-xl" show="loadmodalajuanProgram" title="Ajuan Program Ikatan" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Pencairan Program Ikatan");
            $("#loadmodal").load("/pencairanprogramikatan/create");
        });

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(document).on('click', '#no_pengajuan_search', function(e) {
            e.preventDefault();
            $("#modalajuanProgram").modal("show");
            $("#loadmodalajuanProgram").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodalajuanProgram").load("/ajuanprogramikatan/getajuanprogramikatan");

        });

        $(document).on('click', '.pilihajuan', function(event) {
            var rowData = $(this).closest('tr').find('td');
            var noPengajuan = rowData.eq(1).text(); // No Pengajuan
            var noDokumen = rowData.eq(2).text(); // No. Dokumen
            var tanggal = rowData.eq(3).text(); // Tanggal
            var program = rowData.eq(4).text(); // Program
            var cabang = rowData.eq(5).text(); // Cabang
            var periode = rowData.eq(6).text(); // Periode

            // Lakukan sesuatu dengan data yang diambil, misalnya menampilkan di modal
            console.log(noPengajuan, noDokumen, tanggal, program, cabang, periode);
            $(document).find("#tabeldataajuan").find("#no_pengajuan_text").text(noPengajuan);
            $(document).find("#tabeldataajuan").find("#nomor_dokumen").text(noDokumen);
            $(document).find("#tabeldataajuan").find("#tanggal").text(tanggal);
            $(document).find("#tabeldataajuan").find("#nama_program").text(program);
            $(document).find("#tabeldataajuan").find("#nama_cabang").text(cabang);
            $(document).find("#tabeldataajuan").find("#periode").text(periode);
            $(document).find("#no_pengajuan").val(noPengajuan);
            $("#modalajuanProgram").modal("hide");
        });
    });
</script>
@endpush
