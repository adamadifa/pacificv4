@extends('layouts.app')
@section('titlepage', 'Penysesuaian Upah')

@section('content')
@section('navigasi')
    <span>Penyesuaian Upah</span>
@endsection
<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('penyupah.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah Karyawan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>Kode</th>
                                <td>{{ $penyupah->kode_gaji }}</td>
                            </tr>
                            <tr>
                                <th>Bulan</th>
                                <td>{{ $namabulan[$penyupah->bulan] }}</td>
                            </tr>
                            <tr>
                                <th>Tahun</th>
                                <td>{{ $penyupah->tahun }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nik</th>
                                        <th>Nama</th>
                                        <th>Pengurang</th>
                                        <th>Penambah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailpenyupah as $d)
                                        <tr>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td class="text-end">{{ formatAngka($d->pengurang) }}</td>
                                            <td class="text-end">{{ formatAngka($d->penambah) }}</td>
                                            <td>
                                                <form method="POST" name="deleteform" class="deleteform"
                                                    action="{{ route('penyupah.deletekaryawan', [Crypt::encrypt($d->kode_gaji), Crypt::encrypt($d->nik)]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm me-1">
                                                        <i class="ti ti-trash text-danger"></i>
                                                    </a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div style="float: right;">
                            {{ $badstok->links() }}
                        </div> --}}
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
        $('#btnCreate').click(function() {
            let kode_gaji = "{{ Crypt::encrypt($penyupah->kode_gaji) }}";
            $('#modal').modal('show');
            $('#modal').find('.modal-title').text('Tambah Karyawan');
            $('#loadmodal').load('/penyupah/' + kode_gaji + '/tambahkaryawan');
        });
    });
</script>
@endpush
