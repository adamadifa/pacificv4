@extends('layouts.app')
@section('titlepage', 'Detail Penyesuaian Upah')

@section('style')
<style>
    #tableDetail th {
        padding: 0.55rem 0.75rem !important;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
        white-space: nowrap;
        vertical-align: middle;
    }
    #tableDetail td {
        padding: 0.45rem 0.75rem !important;
        font-size: 0.84rem;
        white-space: nowrap;
        vertical-align: middle;
    }
    #tableDetail tfoot td {
        padding: 0.55rem 0.75rem !important;
        font-size: 0.84rem;
        white-space: nowrap;
        vertical-align: middle;
    }
    .btn-action-icon {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
@section('navigasi')
    <a href="{{ route('penyupah.index') }}" class="text-muted">Penyesuaian Upah</a> / <span class="fw-bold">Detail {{ $namabulan[$penyupah->bulan] }} {{ $penyupah->tahun }}</span>
@endsection

<div class="row g-3">
    {{-- Header Action Card --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="ti ti-wallet fs-3"></i>
                            </span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="mb-0 fw-bold">Periode {{ $namabulan[$penyupah->bulan] }} {{ $penyupah->tahun }}</h5>
                                <span class="badge bg-label-primary font-monospace">{{ $penyupah->kode_gaji }}</span>
                            </div>
                            <small class="text-muted">Kelola penambahan dan pengurangan gaji karyawan untuk periode ini</small>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('penyupah.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="{{ route('penyupah.downloadtemplate', Crypt::encrypt($penyupah->kode_gaji)) }}" class="btn btn-outline-success btn-sm shadow-sm">
                            <i class="ti ti-file-spreadsheet me-1"></i> Download Template
                        </a>
                        @can('penyupah.create')
                            <button type="button" class="btn btn-label-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="ti ti-upload me-1"></i> Import Excel
                            </button>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                                <i class="ti ti-user-plus me-1"></i> Tambah Karyawan
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik / Ringkasan Penyesuaian --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted">Total Karyawan</span>
                        <h4 class="card-title mb-0 fw-bold">{{ number_format($total_karyawan) }} <small class="text-muted fs-6 fw-normal">Orang</small></h4>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary shadow-sm">
                            <i class="ti ti-users fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted">Total Penambah (+)</span>
                        <h4 class="card-title mb-0 fw-bold text-success">+ Rp {{ formatAngka($total_penambah) }}</h4>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success shadow-sm">
                            <i class="ti ti-circle-plus fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted">Total Pengurang (-)</span>
                        <h4 class="card-title mb-0 fw-bold text-danger">- Rp {{ formatAngka($total_pengurang) }}</h4>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger shadow-sm">
                            <i class="ti ti-circle-minus fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted">Net Penyesuaian</span>
                        <h4 class="card-title mb-0 fw-bold {{ $total_net >= 0 ? 'text-primary' : 'text-danger' }}">
                            {{ $total_net < 0 ? '-' : '+' }} Rp {{ formatAngka(abs($total_net)) }}
                        </h4>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info shadow-sm">
                            <i class="ti ti-scale fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Karyawan --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="m-0 fw-bold text-primary"><i class="ti ti-list-details me-2"></i>Daftar Karyawan Disesuaikan</h6>
                        <span class="badge bg-label-primary rounded-pill">{{ count($detailpenyupah) }}</span>
                    </div>
                    <div class="input-group input-group-merge input-group-sm" style="max-width: 280px;">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" id="searchKaryawan" class="form-control" placeholder="Cari NIK, Nama, Cabang...">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0" id="tableDetail">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Cabang</th>
                                <th class="text-end">Penambah (+)</th>
                                <th class="text-end">Pengurang (-)</th>
                                <th class="text-end">Net Selisih</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailpenyupah as $index => $d)
                                @php
                                    $net_item = $d->penambah - $d->pengurang;
                                @endphp
                                <tr class="item-row">
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-label-secondary font-monospace">{{ $d->nik }}</span>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        {{ $d->nama_karyawan }}
                                    </td>
                                    <td>
                                        {{ $d->nama_jabatan ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $d->nama_dept ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $d->nama_cabang ?? '-' }}</span>
                                    </td>
                                    <td class="text-end fw-bold {{ $d->penambah > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ $d->penambah > 0 ? '+ Rp ' . formatAngka($d->penambah) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold {{ $d->pengurang > 0 ? 'text-danger' : 'text-muted' }}">
                                        {{ $d->pengurang > 0 ? '- Rp ' . formatAngka($d->pengurang) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold {{ $net_item >= 0 ? 'text-primary' : 'text-danger' }}">
                                        {{ $net_item < 0 ? '-' : ($net_item > 0 ? '+' : '') }} Rp {{ formatAngka(abs($net_item)) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            @can('penyupah.edit')
                                                <a href="#" class="btn btn-action-icon btn-sm btn-label-primary btnEdit shadow-sm rounded"
                                                    title="Edit Nominal"
                                                    nik="{{ Crypt::encrypt($d->nik) }}">
                                                    <i class="ti ti-edit fs-6"></i>
                                                </a>
                                            @endcan
                                            @can('penyupah.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline m-0"
                                                    action="{{ route('penyupah.deletekaryawan', [Crypt::encrypt($d->kode_gaji), Crypt::encrypt($d->nik)]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="btn btn-action-icon btn-sm btn-label-danger delete-confirm shadow-sm rounded" title="Hapus Karyawan">
                                                        <i class="ti ti-trash fs-6"></i>
                                                    </a>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-center my-3">
                                            <i class="ti ti-users-minus fs-1 text-muted mb-2 d-block"></i>
                                            <h6 class="fw-bold mb-1">Belum Ada Karyawan Disesuaikan</h6>
                                            <p class="text-muted small mb-3">Tambahkan karyawan secara manual atau import sekaligus dari file Excel.</p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('penyupah.downloadtemplate', Crypt::encrypt($penyupah->kode_gaji)) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="ti ti-file-spreadsheet me-1"></i> Download Template
                                                </a>
                                                @can('penyupah.create')
                                                    <button type="button" class="btn btn-label-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                                                        <i class="ti ti-upload me-1"></i> Import Excel
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm" id="btnCreateEmpty">
                                                        <i class="ti ti-user-plus me-1"></i> Tambah Karyawan
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($detailpenyupah->count() > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="6" class="text-center text-uppercase">Total</td>
                                    <td class="text-end text-success">+ Rp {{ formatAngka($total_penambah) }}</td>
                                    <td class="text-end text-danger">- Rp {{ formatAngka($total_pengurang) }}</td>
                                    <td class="text-end {{ $total_net >= 0 ? 'text-primary' : 'text-danger' }}">
                                        {{ $total_net < 0 ? '-' : '+' }} Rp {{ formatAngka(abs($total_net)) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-label-success border-bottom py-3">
                <h5 class="modal-title fw-bold text-success d-flex align-items-center">
                    <i class="ti ti-file-import me-2 fs-4"></i> Import Penyesuaian Upah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penyupah.import', Crypt::encrypt($penyupah->kode_gaji)) }}" method="POST" enctype="multipart/form-data" id="formImportExcel">
                @csrf
                <div class="modal-body py-4">
                    <div class="alert alert-primary d-flex align-items-start mb-3" role="alert">
                        <i class="ti ti-info-circle me-2 fs-4 flex-shrink-0 mt-1"></i>
                        <div class="small">
                            <strong>Panduan Import:</strong>
                            <ol class="ps-3 mb-0 mt-1">
                                <li>Unduh template Excel dengan tombol <strong>Download Template</strong>.</li>
                                <li>Isi nominal pada kolom <strong>Penambah</strong> dan <strong>Pengurang</strong> untuk karyawan yang bersangkutan.</li>
                                <li>Baris karyawan dengan nilai Penambah & Pengurang <code>0</code> akan otomatis dilewati.</li>
                                <li>Jika NIK sudah ada, nilai nominal akan otomatis diperbarui (update).</li>
                            </ol>
                        </div>
                    </div>

                    <div class="mb-3 text-end">
                        <a href="{{ route('penyupah.downloadtemplate', Crypt::encrypt($penyupah->kode_gaji)) }}" class="btn btn-outline-success btn-sm">
                            <i class="ti ti-download me-1"></i> Unduh File Template Excel
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required id="fileExcel">
                        <small class="text-muted">Ukuran maksimal file: 5 MB</small>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitImport">
                        <i class="ti ti-upload me-1"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Container Modal Ajax Tambah & Edit Karyawan --}}
<x-modal-form id="modal" size="" show="loadmodal" title="" />

@endsection

@push('myscript')
<script>
    $(function() {
        let kode_gaji = "{{ Crypt::encrypt($penyupah->kode_gaji) }}";

        // Tambah Karyawan Modal
        function openTambahModal() {
            $('#modal').modal('show');
            $('#modal').find('.modal-title').html('<i class="ti ti-user-plus me-2 text-primary"></i> Tambah Karyawan');
            $('#loadmodal').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            $('#loadmodal').load('/penyupah/' + kode_gaji + '/tambahkaryawan');
        }

        $('#btnCreate').click(function(e) {
            e.preventDefault();
            openTambahModal();
        });

        $('#btnCreateEmpty').click(function(e) {
            e.preventDefault();
            openTambahModal();
        });

        // Edit Karyawan Modal
        $(document).on('click', '.btnEdit', function(e) {
            e.preventDefault();
            let nik = $(this).attr('nik');
            $('#modal').modal('show');
            $('#modal').find('.modal-title').html('<i class="ti ti-edit me-2 text-primary"></i> Edit Penyesuaian Upah');
            $('#loadmodal').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            $('#loadmodal').load('/penyupah/' + kode_gaji + '/' + nik + '/editkaryawan');
        });

        // Live Search Karyawan di Tabel
        $('#searchKaryawan').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#tableDetail tbody tr.item-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Loading state saat Import form disubmit
        $('#formImportExcel').submit(function() {
            let file = $('#fileExcel').val();
            if (!file) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Pilih file Excel terlebih dahulu!',
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }
            $('#btnSubmitImport').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Mengimport Data...
            `);
        });
    });
</script>
@endpush
