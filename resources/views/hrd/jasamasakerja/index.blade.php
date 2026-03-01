@extends('layouts.app')
@section('titlepage', 'Jasa Masa Kerja')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jasa Masa Kerja</h4>
            <small class="text-muted">Mengelola data pembayaran jasa masa kerja karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-receipt me-1"></i>Jasa Masa Kerja</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('jasamasakerja.index') }}" class="mb-2">
            <div class="row g-2">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search" icon="ti ti-user" />
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt me-2"></i>Data Jasa Masa Kerja</h6>
                    @can('jasamasakerja.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat JMK</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.Bukti</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">NIK</th>
                            <th class="text-white" style="width: 15%">Nama</th>
                            <th class="text-white">Jabatan</th>
                            <th class="text-white">Dept.</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white text-end">Jumlah</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($jasamasakerja as $d)
                            <tr>
                                <td>{{ $d->kode_jmk }}</td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-bold">{{ $d->nama_karyawan }}</span></td>
                                <td>{{ $d->nama_jabatan }}</td>
                                <td>{{ $d->kode_dept }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td class="text-end fw-semibold text-primary">{{ formatRupiah($d->jumlah) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('jasamasakerja.edit')
                                            <a href="#" class="btnEdit text-info" kode_jmk="{{ Crypt::encrypt($d->kode_jmk) }}" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('jasamasakerja.delete')
                                            <form method="POST" class="delete d-inline" action="{{ route('jasamasakerja.delete', Crypt::encrypt($d->kode_jmk)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $jasamasakerja->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Bayar Jasa Masa Kerja");
            $("#loadmodal").load(`/jasamasakerja/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_jmk = $(this).attr("kode_jmk");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Bayar Jasa Masa Kerja");
            $("#loadmodal").load(`/jasamasakerja/${kode_jmk}/edit`);
        });
    });
</script>
@endpush
