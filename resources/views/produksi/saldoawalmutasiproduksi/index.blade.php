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
                    <a href="{{ route('samutasiproduksi.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
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
                                                <option {{ Request('tahun') == $t ? 'selected' : '' }}
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
                                                    @can('samutasiproduksi.show')
                                                        <div>
                                                            <a href="#" class="me-2 show"
                                                                kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('samutasiproduksi.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('samutasiproduksi.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
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
                        <div style="float: right;">
                            {{-- {{ $bpbj->links() }} --}}
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

        $(".show").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/samutasiproduksi/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
