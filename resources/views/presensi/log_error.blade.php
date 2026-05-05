@extends('layouts.app')
@section('titlepage', 'Log Error Presensi')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Log Error Presensi</h4>
            <small class="text-muted">Daftar error saat proses presensi via Mobile Apps.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-calendar-x me-1"></i>Log Error Presensi</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-12">
        <form action="{{ route('presensi.logerror') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-2 col-md-4">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-input-with-icon label="Cari Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan" icon="ti ti-user" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar-x me-2"></i>Data Log Error</h6>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">No</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Waktu</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NIK</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Nama Karyawan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Status</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Pesan Error</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($log_error as $d)
                            <tr>
                                <td>{{ $loop->iteration + $log_error->firstItem() - 1 }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($d->jam)) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-semibold">{{ $d->nama_karyawan }}</span></td>
                                <td>
                                    @if ($d->status_presensi == 'masuk')
                                        <span class="badge bg-label-success">Masuk</span>
                                    @else
                                        <span class="badge bg-label-danger">Pulang</span>
                                    @endif
                                </td>
                                <td style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $d->error_message }}
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btnShowPayload text-info" payload="{{ $d->payload }}" data-bs-toggle="tooltip" title="Lihat Payload">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $log_error->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modalPayload" size="modal-lg" show="loadpayload" title="Detail Payload" />

@endsection

@push('myscript')
<script>
    $(function() {
        $(".btnShowPayload").click(function(e) {
            e.preventDefault();
            const payload = $(this).attr("payload");
            $("#modalPayload").modal("show");
            $("#loadpayload").html('<pre class="p-3 bg-light" style="white-space: pre-wrap; word-wrap: break-word;">' + JSON.stringify(JSON.parse(payload), null, 4) + '</pre>');
        });
    });
</script>
@endpush
