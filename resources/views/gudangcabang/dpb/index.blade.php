@extends('layouts.app')
@section('titlepage', 'Data Pengambilan Barang')

@section('content')
@section('navigasi')
    <span>Data Pengambilan Barang (DPB)</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. DPB</th>
                                        <th>Tanggal</th>
                                        <th>Salesman</th>
                                        <th>Cabang</th>
                                        <th>Tujuan</th>
                                        <th>No. Kendaraan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dpb as $d)
                                        <tr>
                                            <td>{{ $d->no_dpb }}</td>
                                            <td>{{ DateToIndo($d->tanggal_ambil) }}</td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>{{ $d->tujuan }}</td>
                                            <td>{{ $d->no_polisi }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-modal-form id="modal" size="" show="loadmodal" title="" />
    @endsection
    @push('myscript')
    @endpush
