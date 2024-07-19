@extends('layouts.app')
@section('titlepage', 'Atur Hari Libur')

@section('content')
@section('navigasi')
    <span>Atur Hari Libur</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('harilibur.setharilibur')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Karyawan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>Kode Libur</th>
                                <td class="text-end">{{ $harilibur->kode_libur }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($harilibur->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td class="text-end">{{ $harilibur->nama_kategori }}</td>
                            </tr>
                            @if (!empty($harilibur->tanggal_diganti))
                                <tr>
                                    <th>Pengganti Tanggal</th>
                                    <td class="text-end">{{ DateToIndo($harilibur->tanggal_diganti) }}</td>
                                </tr>
                            @endif
                            @if (!empty($harilibur->tanggal_limajam))
                                <tr>
                                    <th>Tanggal 5 Jam</th>
                                    <td class="text-end">{{ DateToIndo($harilibur->tanggal_limajam) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Keterangan</th>
                                <td class="text-end">{{ $harilibur->keterangan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nik</th>
                                    <th>Nama Karyawan</th>
                                    <th>Dept</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="loadliburkaryawan">

                            </tbody>
                        </table>
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
        function loadliburkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#loadliburkaryawan").load(`/harilibur/${kode_libur}/getkaryawanlibur`);
        }
        loadliburkaryawan();
    });
</script>
@endpush
