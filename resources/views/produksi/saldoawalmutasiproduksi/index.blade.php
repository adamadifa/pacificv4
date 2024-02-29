@extends('layouts.app')
@section('titlepage', 'Saldo Awal Mutasi Produksi')

@section('content')
@section('navigasi')
    <span>Saldo Awal Mutasi Produksi</span>
@endsection

<div class="row">
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpbj.create')
                    <a href="#" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                        Buat Saldo Awal</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('samutasiproduksi.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($nama_bulan as $d)
                                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option value="{{ $t }}">{{ $t }}</option>
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
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Tanggal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $bpbj->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
