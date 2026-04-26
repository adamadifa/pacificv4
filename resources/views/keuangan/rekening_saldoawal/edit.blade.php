@extends('layouts.app')
@section('titlepage', 'Edit Saldo Awal Rekening')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Edit Saldo Awal Rekening</h4>
            <small class="text-muted">Update saldo awal harian per rekening bank.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('samutasibank.index') }}">Saldo Awal Rekening</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
@endsection

<form action="{{ route('samutasibank.update', Crypt::encrypt($saldo_awal->kode_saldo_awal)) }}" method="POST" id="formSaldoAwal">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            {{-- Form Tanggal dalam card seperti di index --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label fw-bold">Tanggal</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" name="tanggal" id="tanggal" class="form-control flatpickr-date" value="{{ $saldo_awal->tanggal }}" required readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel dalam card dengan header seperti index --}}
            <div class="card shadow-sm border">
                <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Saldo Awal: {{ $saldo_awal->kode_saldo_awal }}</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">NO</th>
                                    <th class="text-white text-center" style="background-color: #002e65 !important;">NAMA BANK / REKENING</th>
                                    <th class="text-white text-center" style="background-color: #002e65 !important; width: 40%;">SALDO AWAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bank as $d)
                                    @php
                                        $jumlah_val = isset($details[$d->kode_bank]) ? formatAngka($details[$d->kode_bank]) : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="kode_bank[]" value="{{ $d->kode_bank }}">
                                            <div class="fw-bold">{{ $d->nama_bank }}</div>
                                            <small class="text-muted">{{ $d->no_rekening }}</small>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" name="jumlah[]" class="form-control text-end money" placeholder="0" value="{{ $jumlah_val }}">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('samutasibank.index') }}" class="btn btn-label-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Update Data</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney({
            prefix: '',
            thousands: '.',
            decimal: ',',
            precision: 0
        });
    });
</script>
@endpush
