@extends('layouts.app')
@section('titlepage', 'Saldo Awal Rekening')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Saldo Awal Rekening</h4>
            <small class="text-muted">Manajemen saldo awal harian rekening bank.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-database-import me-1"></i>Saldo Awal Rekening</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">


        {{-- Filter Section --}}
        <form action="{{ URL::current() }}">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-10 col-md-9 col-sm-12">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" name="tanggal" id="tanggal" class="form-control flatpickr-date" 
                                    placeholder="Filter Tanggal" value="{{ Request('tanggal') }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-database-import me-2"></i>Data Saldo Awal Rekening</h6>
                    @can('samutasibank.create')
                        <a href="{{ route('samutasibank.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="ti ti-plus me-1"></i> Buat Saldo Awal
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">NO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 10%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saldo_awal as $d)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $saldo_awal->firstItem() - 1 }}</td>
                                <td class="text-center fw-bold">{{ $d->kode_saldo_awal }}</td>
                                <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('samutasibank.index')
                                            <a href="{{ route('samutasibank.show', Crypt::encrypt($d->kode_saldo_awal)) }}" class="text-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-eye fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('samutasibank.edit')
                                            <a href="{{ route('samutasibank.edit', Crypt::encrypt($d->kode_saldo_awal)) }}" class="text-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('samutasibank.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('samutasibank.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </a>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $saldo_awal->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
    });
</script>
@endpush
