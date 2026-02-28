@extends('layouts.app')
@section('titlepage', 'Saldo Awal Mutasi Produksi')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Saldo Awal Mutasi Produksi</h4>
            <small class="text-muted">Mengelola data saldo awal mutasi produksi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Produksi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-description me-1"></i>Saldo Awal Mutasi</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasiproduksi')
        </div>

        {{-- Filter Section (Below Navigation) --}}
        <form action="{{ route('samutasiproduksi.index') }}">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-0">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                    value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group mb-0">
                        <label for="tahun" class="form-label">Tahun</label>
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
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                </div>
            </div>
        </form>

        {{-- Tab Content / Data Card Area --}}
        <div class="tab-content p-0 shadow-none bg-transparent">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                {{-- Data Card --}}
                <div class="card shadow-sm border">
                    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-description me-2"></i>Data Saldo Awal</h6>
                            @can('samutasiproduksi.create')
                                <a href="{{ route('samutasiproduksi.create') }}" class="btn btn-primary btn-sm shadow-sm">
                                    <i class="ti ti-plus me-1"></i> Buat Saldo Awal
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                                    <th class="text-white" style="background-color: #002e65 !important;">BULAN</th>
                                    <th class="text-white" style="background-color: #002e65 !important;">TAHUN</th>
                                    <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                                    <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($saldo_awal as $d)
                                    <tr>
                                        <td><span class="fw-bold text-primary">{{ $d->kode_saldo_awal }}</span></td>
                                        <td>{{ $nama_bulan[$d->bulan] }}</td>
                                        <td>{{ $d->tahun }}</td>
                                        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('samutasiproduksi.show')
                                                    <a href="#" class="showSaldoawal text-info" data-bs-toggle="tooltip" title="Detail"
                                                        kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}">
                                                        <i class="ti ti-file-description fs-5"></i>
                                                    </a>
                                                @endcan
                                                @can('samutasiproduksi.delete')
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('samutasiproduksi.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                            data-bs-toggle="tooltip" title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </button>
                                                    </form>
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
            $("#loaddetail").load('/samutasiproduksi/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
