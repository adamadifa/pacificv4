@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Rekening')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Buat Saldo Awal Rekening</h4>
            <small class="text-muted">Input saldo awal harian per rekening bank.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('samutasibank.index') }}">Saldo Awal Rekening</a>
                </li>
                <li class="breadcrumb-item active">Buat</li>
            </ol>
        </nav>
    </div>
@endsection

<form action="{{ route('samutasibank.store') }}" method="POST" id="formSaldoAwal">
    @csrf
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
                                <input type="text" name="tanggal" id="tanggal" class="form-control flatpickr-date" value="{{ date('Y-m-d') }}" required readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel dalam card dengan header seperti index --}}
            <div class="card shadow-sm border">
                <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-database-import me-2"></i>Input Saldo Awal Rekening</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('samutasibank.downloadtemplate') }}" class="btn btn-success btn-sm">
                                <i class="ti ti-download me-1"></i> Template
                            </a>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="ti ti-file-import me-1"></i> Import
                            </button>
                        </div>
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
                                                <input type="text" name="jumlah[]" class="form-control text-end money" placeholder="0">
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
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Saldo Awal Rekening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('samutasibank.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-1"></i> Pastikan format file sesuai dengan template yang diunduh.
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Tanggal Saldo Awal</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            <input type="text" name="tanggal" class="form-control flatpickr-date" value="{{ date('Y-m-d') }}" required readonly>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-file-import me-1"></i> Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

        $("#formSaldoAwal").submit(function() {
            var tanggal = $("#tanggal").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
        });
    });
</script>
@endpush
