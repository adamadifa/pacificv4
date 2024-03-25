@extends('layouts.app')
@section('titlepage', 'Saldo Awal Barang Produksi')

@section('content')
@section('navigasi')
    <span>Saldo Awal Barang Produksi</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasibarangproduksi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('sabarangproduksi.create')
                        <a href="{{ route('sabarangproduksi.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus me-2"></i>
                            Buat Saldo Awal</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('sabarangproduksi.index') }}">
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
                                        <button class="btn btn-primary"><i
                                                class="ti ti-icons ti-search me-1"></i></button>
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
                                        @foreach ($saldo_awal as $d)
                                            <tr>
                                                <td>{{ $d->kode_saldo_awal }}</td>
                                                <td>{{ $nama_bulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('sabarangproduksi.show')
                                                            <div>
                                                                <a href="#" class="me-2 showSaldoawal"
                                                                    kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('sabarangproduksi.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('sabarangproduksi.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </td>
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
</div>

<x-modal-form id="mdldetail" size="" show="loaddetail" title="Detail Saldo Awal " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".showSaldoawal").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/sabarangproduksi/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
