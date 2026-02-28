@extends('layouts.app')
@section('titlepage', 'Opname Gudang Logistik')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Opname Gudang Logistik</h4>
            <small class="text-muted">Manajemen stok opname gudang logistik.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Logistik</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-clipboard-list me-1"></i>Opname</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudanglogistik')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('opgudanglogistik.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-3 col-sm-12">
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
                        <div class="col-lg-2 col-md-2 col-sm-12">
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
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_kategori" id="kode_kategori" class="form-select">
                                    <option value="">Kategori</option>
                                    @foreach ($kategori as $d)
                                        <option {{ Request('kode_kategori') == $d->kode_kategori ? 'selected' : '' }}
                                            value="{{ $d->kode_kategori }}">{{ strtoupper($d->nama_kategori) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Data Opname</h6>
                    @can('opgudanglogistik.create')
                        <a href="{{ route('opgudanglogistik.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="ti ti-plus me-1"></i> Tambah Data
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
                            <th class="text-white" style="background-color: #002e65 !important;">KATEGORI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if ($opname->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Data tidak ditemukan</td>
                            </tr>
                        @else
                            @foreach ($opname as $d)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $d->kode_opname }}</span></td>
                                    <td>{{ $nama_bulan[$d->bulan] }}</td>
                                    <td>{{ $d->tahun }}</td>
                                    <td>{{ $d->nama_kategori }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('opgudanglogistik.edit')
                                                <a href="{{ route('opgudanglogistik.edit', Crypt::encrypt($d->kode_opname)) }}"
                                                    class="text-success" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                            @endcan
                                            @can('opgudanglogistik.show')
                                                <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                    kode_opname="{{ Crypt::encrypt($d->kode_opname) }}">
                                                    <i class="ti ti-file-description fs-5"></i>
                                                </a>
                                            @endcan
                                            @can('opgudanglogistik.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('opgudanglogistik.delete', Crypt::encrypt($d->kode_opname)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="Detail Opname" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(".btnShow").click(function(e) {
            var kode_opname = $(this).attr("kode_opname");
            e.preventDefault();
            $('#modal').modal("show");
            $("#loadmodal").load('/opgudanglogistik/' + kode_opname + '/show');
        });
    });
</script>
@endpush
