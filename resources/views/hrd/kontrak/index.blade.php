@extends('layouts.app')
@section('titlepage', 'Kontrak Kerja')

@section('content')
@section('navigasi')
    <span>Kontrak Kerja</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('kontrakkerja.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Kontrak Kerja</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kontrakkerja.index') }}">
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
                                        <th>No.Kontrak</th>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th style="width: 15%">Nama</th>
                                        <th>Jabatan</th>
                                        <th>Dept.</th>
                                        <th>Cabang</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Lama</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kontrak as $d)
                                        @php
                                            $lamabulan = calculateMonthsKontrak($d->dari, $d->sampai);
                                            $color_kontrak = $d->status_kontrak === '0' ? 'bg-danger text-white' : '';
                                        @endphp
                                        <tr class="{{ $color_kontrak }}">
                                            <td>{{ $d->no_kontrak }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ textUpperCase($d->nama_karyawan) }}</td>
                                            <td>
                                                @if (!empty($d->alias_jabatan))
                                                    {{ $d->alias_jabatan }}
                                                @else
                                                    {{ $d->nama_jabatan }}
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td><span class="badge bg-success">{{ formatIndo($d->dari) }}</span></td>
                                            <td class="text-center">
                                                {{-- {{ $d->masa_kontrak }} --}}
                                                @if ($d->masa_kontrak == 'KT' || $d->dari == $d->sampai)
                                                    <i class="ti ti-infinity {{ $d->status_kontrak == '1' ? 'text-danger' : 'text-white' }}"></i>
                                                @else
                                                    <span class="badge bg-danger">
                                                        {{ formatIndo($d->sampai) }}
                                                    </span>
                                                @endif

                                            </td>
                                            <td>{{ $lamabulan }} Bln</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('kontrakkerja.edit')
                                                        <a href="#" class="btnEdit me-1" no_kontrak="{{ Crypt::encrypt($d->no_kontrak) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('kontrakkerja.show')
                                                        <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak)) }}" class="me-1"
                                                            target="_blank">
                                                            <i class="ti ti-printer text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('kontrakkerja.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('kontrakkerja.delete', Crypt::encrypt($d->no_kontrak)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $kontrak->links() }}
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
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Kontrak");
            $("#loadmodal").load(`/kontrakkerja/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_kontrak = $(this).attr("no_kontrak");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Kontrak");
            $("#loadmodal").load(`/kontrakkerja/${no_kontrak}/edit`);
        });
    });
</script>
@endpush
