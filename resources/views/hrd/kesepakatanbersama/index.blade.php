@extends('layouts.app')
@section('titlepage', 'Kesepakatan Bersama')

@section('content')
@section('navigasi')
    <span>Kesepakatan Bersama</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kesepakatanbersama.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search"
                                        icon="ti ti-user" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.KB</th>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Dept.</th>
                                        <th>Cabang</th>
                                        <th>Jeda</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kesepakatanbersama as $d)
                                        @php
                                            $jmlhari = hitungJumlahHari($d->tanggal, date('Y-m-d'));
                                        @endphp
                                        <tr>
                                            <td>{{ $d->no_kb }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ $jmlhari }} Hari</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('kb.show')
                                                        <a href="{{ route('kesepakatanbersama.cetak', Crypt::encrypt($d->no_kb)) }}" class="me-1"
                                                            target="_blank">
                                                            <i class="ti ti-printer text-primary"></i>
                                                        </a>
                                                    @endcan

                                                    @can('kontrakkerja.create')
                                                        @if (empty($d->no_kontrak_baru))
                                                            <a href="#" class="btnCreatekontrak  me-1"
                                                                kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}">
                                                                <i class="ti ti-file-plus text-danger"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak_baru)) }}"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-printer text-info"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('kb.edit')
                                                        <a href="#" class="btnPotongan me-1" no_kb = "{{ Crypt::encrypt($d->no_kb) }}">
                                                            <i class="ti ti-tag text-warning"></i>
                                                        </a>
                                                    @endcan
                                                    @can('kb.delete')
                                                        @if (empty($d->no_kontrak_baru))
                                                            <form method="POST"
                                                                action="{{ route('kesepakatanbersama.delete', Crypt::encrypt($d->no_kb)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $kesepakatanbersama->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };


        $(".btnPotongan").click(function(e) {
            e.preventDefault();
            var no_kb = $(this).attr("no_kb");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Potongan");
            $("#loadmodal").load(`/kesepakatanbersama/${no_kb}/potongan`);
        });

        $(".btnPotongan").click(function(e) {
            e.preventDefault();
            var no_kb = $(this).attr("no_kb");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Potongan");
            $("#loadmodal").load(`/kesepakatanbersama/${no_kb}/potongan`);
        });

        $(".btnCreatekontrak").click(function(e) {
            e.preventDefault();
            var kode_penilaian = $(this).attr("kode_penilaian");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Kontrak");
            $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/createkontrak`);
        });
    });
</script>
@endpush
