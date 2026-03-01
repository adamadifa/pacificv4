@extends('layouts.app')
@section('titlepage', 'Saldo Awal Buku Besar')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Saldo Awal Buku Besar</h4>
            <small class="text-muted">Manajemen saldo awal buku besar (CoA) per periode.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-book me-1"></i>Saldo Awal Buku Besar</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Filter Section (Outside Card) --}}
        <form action="{{ route('saldoawalbukubesar.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Semua Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
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
                        <div class="col-auto">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Data Saldo Awal</h6>
                    @can('saldoawalbukubesar.create')
                        <a href="{{ route('saldoawalbukubesar.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Buat Saldo Awal</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr style="background-color: #002e65;">
                            <th class="py-3 text-white" style="padding-left: 15px;">KODE</th>
                            <th class="py-3 text-white">BULAN</th>
                            <th class="py-3 text-white">TAHUN</th>
                            <th class="py-3 text-center text-white" style="width: 100px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($saldoawalbukubesar as $d)
                            <tr>
                                <td class="py-2" style="padding-left: 15px;"><span class="fw-semibold">{{ $d->kode_saldo_awal }}</span></td>
                                <td class="py-2">{{ $nama_bulan[$d->bulan] }}</td>
                                <td class="py-2">{{ $d->tahun }}</td>
                                <td class="py-2">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('saldoawalbukubesar.show')
                                            <a href="#" class="btnShow text-info"
                                                kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}" data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="ti ti-file-description fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('saldoawalbukubesar.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('saldoawalbukubesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ti ti-database-off d-block mb-1 fs-2"></i>
                                    Tidak ada data ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="Detail Saldo Awal Buku Besar" />
@endsection

@push('myscript')
<script>
    $(function() {
        $(".btnShow").click(function(e) {
            var kode_saldo_awal = $(this).attr("kode_saldo_awal");
            e.preventDefault();
            $('#modal').modal("show");
            $("#loadmodal").load('/saldoawalbukubesar/' + kode_saldo_awal + '/show');
        });
    });
</script>
@endpush
