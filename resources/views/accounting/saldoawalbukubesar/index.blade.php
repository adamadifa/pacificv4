@extends('layouts.app')
@section('titlepage', 'Saldo Awal Buku Besar')

@section('content')
@section('navigasi')
    <span>Saldo Awal Buku Besar</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('saldoawalbukubesar.create')
                    <a href="{{ route('saldoawalbukubesar.create') }}" class="btn btn-primary"><i
                            class="ti ti-plus me-1"></i>Buat Saldo Awal
                        Buku Besar</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('saldoawalbukubesar.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                    value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option
                                                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                        @else
                                                        {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                    value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                                        <th>Cabang</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saldoawalbukubesar as $d)
                                        <tr>
                                            <td>{{ $d->kode_saldo_awal }}</td>
                                            <td>{{ $nama_bulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>
                                            <div class="d-flex">
                                                <a href="{{ route('saldoawalbukubesar.edit', Crypt::encrypt($d->kode_saldo_awal)) }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i></a>
                                            </div>
                                            </td
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
