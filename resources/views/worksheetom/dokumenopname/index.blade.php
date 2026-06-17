@extends('layouts.app')
@section('titlepage', 'Upload Dok. Opname')

@section('content')
@section('navigasi')
    <span>Upload Dok. Opname</span>
@endsection
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Dokumen Opname</h5>
                <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Upload Dok. Opname</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('worksheetom.dokumenopname') }}">
                            <div class="row align-items-center">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <x-input-with-icon label="Dari Tanggal" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ Request('dari') ?? $dari }}" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <x-input-with-icon label="Sampai Tanggal" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ Request('sampai') ?? $sampai }}" />
                                    </div>
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                                <option value="">Semua Cabang</option>
                                                @foreach ($cabang as $d)
                                                    <option value="{{ $d->kode_cabang }}" {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}>
                                                        {{ textuppercase($d->nama_cabang) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endhasanyrole
                                <div class="col-lg-2 col-sm-12">
                                    <button class="btn btn-primary w-100 mb-3"><i class="ti ti-search me-1"></i> Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        @if ($dokumen_opname->isEmpty())
                            <div class="alert alert-warning text-center">Belum ada dokumen opname yang diupload untuk periode ini.</div>
                        @else
                            @foreach ($dokumen_opname as $d)
                                <div class="card mb-3 border border-light shadow-none" style="border-radius: 6px; background-color: #fcfcfc;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <!-- Branch & Date -->
                                            <div class="col-md-2 col-12 mb-2 mb-md-0">
                                                <span class="badge bg-label-primary px-3 py-2 rounded text-uppercase fw-bold d-block mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                    {{ textUpperCase($d->nama_cabang) }}
                                                </span>
                                                <span class="text-dark fw-bold d-block text-center">{{ formatIndo($d->tanggal) }}</span>
                                            </div>
                                            <!-- Uploader Info -->
                                            <div class="col-md-3 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-user-check text-muted me-2" style="font-size: 1.25rem;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Diupload Oleh</small>
                                                        <span class="text-dark small"><strong>{{ $d->nama_uploader }}</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Documents Uploaded -->
                                            <div class="col-md-4 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column gap-1">
                                                    <div>
                                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">Dokumen Opname:</small>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @if (!empty($d->file_persediaan))
                                                            <a href="{{ url(Storage::url('dokumen_opname/' . $d->file_persediaan)) }}" target="_blank" class="btn btn-xs btn-outline-info px-2 py-1" style="font-size: 0.75rem;">
                                                                <i class="ti ti-box me-1"></i> Persediaan
                                                            </a>
                                                        @else
                                                            <span class="badge bg-label-danger py-1" style="font-size: 0.7rem;">Persediaan (-)</span>
                                                        @endif
                                                        @if (!empty($d->file_kas_kecil))
                                                            <a href="{{ url(Storage::url('dokumen_opname/' . $d->file_kas_kecil)) }}" target="_blank" class="btn btn-xs btn-outline-info px-2 py-1" style="font-size: 0.75rem;">
                                                                <i class="ti ti-wallet me-1"></i> Kas Kecil
                                                            </a>
                                                        @else
                                                            <span class="badge bg-label-danger py-1" style="font-size: 0.7rem;">Kas Kecil (-)</span>
                                                        @endif
                                                        @if (!empty($d->file_kas_besar))
                                                            <a href="{{ url(Storage::url('dokumen_opname/' . $d->file_kas_besar)) }}" target="_blank" class="btn btn-xs btn-outline-info px-2 py-1" style="font-size: 0.75rem;">
                                                                <i class="ti ti-coin me-1"></i> Kas Besar
                                                            </a>
                                                        @else
                                                            <span class="badge bg-label-danger py-1" style="font-size: 0.7rem;">Kas Besar (-)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Approval Status & Actions -->
                                            <div class="col-md-3 col-12 text-md-end d-flex flex-column align-items-md-end gap-2">
                                                <div>
                                                    @if ($d->status_approval == 0)
                                                        <span class="badge bg-label-warning px-3 py-2 rounded text-uppercase fw-semibold" style="font-size: 0.75rem;">
                                                            Waiting Approval
                                                        </span>
                                                    @elseif ($d->status_approval == 1)
                                                        <span class="badge bg-label-success px-3 py-2 rounded text-uppercase fw-semibold" style="font-size: 0.75rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="Disetujui oleh {{ $d->nama_approver }} pada {{ $d->approved_at }}">
                                                            Approved
                                                        </span>
                                                    @elseif ($d->status_approval == 2)
                                                        <span class="badge bg-label-danger px-3 py-2 rounded text-uppercase fw-semibold" style="font-size: 0.75rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="Ditolak oleh {{ $d->nama_approver }} pada {{ $d->approved_at }}">
                                                            Rejected
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex gap-1 align-items-center">
                                                    @hasanyrole(['sales marketing manager', 'super admin'])
                                                        @if ($d->status_approval == 0)
                                                            <form method="POST" action="{{ route('worksheetom.dokumenopname.approve', Crypt::encrypt($d->kode_dokumen_opname)) }}" class="d-inline mb-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-success py-1 px-2" title="Setujui">
                                                                    <i class="ti ti-check me-1"></i> Approve
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('worksheetom.dokumenopname.reject', Crypt::encrypt($d->kode_dokumen_opname)) }}" class="d-inline mb-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-danger py-1 px-2" title="Tolak">
                                                                    <i class="ti ti-x me-1"></i> Reject
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endhasanyrole
                                                    @if ($d->status_approval == 0 || Auth::user()->hasRole('super admin'))
                                                        <form method="POST" class="deleteform d-inline mb-0" action="{{ route('worksheetom.dokumenopname.delete', Crypt::encrypt($d->kode_dokumen_opname)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-xs btn-outline-danger delete-confirm py-1 px-2" title="Hapus">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Upload Dokumen Opname" />

@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/worksheetom/dokumenopname/create');
        });

        $(document).on('click', '.delete-confirm', function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Dokumen ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
