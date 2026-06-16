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
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : (Request('bulan') == '' && date('m') == $d['kode_bulan'] ? 'selected' : '') }}>
                                                    {{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option value="{{ $t }}" {{ Request('tahun') == $t ? 'selected' : (Request('tahun') == '' && date('Y') == $t ? 'selected' : '') }}>{{ $t }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_cabang" id="kode_cabang" class="form-select">
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
                            <div class="alert alert-warning text-center">Belum ada dokumen opname yang diupload.</div>
                        @else
                            @foreach ($dokumen_opname as $d)
                                <div class="card mb-2 border border-light shadow-none" style="border-radius: 6px; background-color: #fcfcfc;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <!-- Branch -->
                                            <div class="col-md-2 col-12 mb-2 mb-md-0">
                                                <span class="badge bg-label-primary px-3 py-2 rounded text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                    {{ textUpperCase($d->nama_cabang) }}
                                                </span>
                                            </div>
                                            <!-- Period -->
                                            <div class="col-md-3 col-sm-6 mb-2 mb-sm-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-calendar text-muted me-2" style="font-size: 1.25rem;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Periode Opname</small>
                                                        <span class="fw-semibold text-dark">{{ $namabulan[$d->bulan] }} {{ $d->tahun }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Upload Info -->
                                            <div class="col-md-5 col-sm-6 mb-3 mb-sm-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-user-check text-muted me-2" style="font-size: 1.25rem;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Diupload Oleh</small>
                                                        <span class="text-dark small">
                                                            <strong>{{ $d->nama_uploader }}</strong> <span class="text-muted">({{ formatIndo($d->tanggal) }})</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Action -->
                                            <div class="col-md-2 col-12 text-md-end">
                                                @if (!empty($d->file_dokumen))
                                                    @php
                                                        $path = Storage::url('dokumen_opname/' . $d->file_dokumen);
                                                    @endphp
                                                    <a href="{{ url($path) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2" title="Lihat/Download Dokumen">
                                                        <i class="ti ti-download me-1"></i> Dokumen
                                                    </a>
                                                @endif
                                                <form method="POST" name="deleteform" class="deleteform d-inline-block mb-0"
                                                    action="{{ route('worksheetom.dokumenopname.delete', Crypt::encrypt($d->kode_dokumen_opname)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-flat-danger text-danger delete-confirm p-1 ms-1" title="Hapus">
                                                        <i class="ti ti-trash" style="font-size: 1.15rem;"></i>
                                                    </button>
                                                </form>
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
