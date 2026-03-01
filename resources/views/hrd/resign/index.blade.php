@extends('layouts.app')
@section('titlepage', 'Resign')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Resign</h4>
            <small class="text-muted">Mengelola data pengunduran diri karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-logout me-1"></i>Resign</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('resign.index') }}" class="mb-2">
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-logout me-2"></i>Data Resign</h6>
                    @can('resign.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat Resign</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th rowspan="2" class="text-white align-middle">Kode</th>
                            <th rowspan="2" class="text-white align-middle">Tanggal</th>
                            <th rowspan="2" class="text-white align-middle">NIK</th>
                            <th rowspan="2" class="text-white align-middle" style="width: 15%">Nama</th>
                            <th rowspan="2" class="text-white align-middle">Jabatan</th>
                            <th rowspan="2" class="text-white align-middle">Dept.</th>
                            <th rowspan="2" class="text-white align-middle">Cabang</th>
                            <th colspan="3" class="text-center text-white border-bottom-0">Piutang</th>
                            <th rowspan="2" class="text-white align-middle">Kategori</th>
                            <th rowspan="2" class="text-white align-middle text-center">#</th>
                        </tr>
                        <tr style="background-color: #002e65;">
                            <th class="text-white border-top-0 border-start text-center">PJP</th>
                            <th class="text-white border-top-0 text-center">Kasbon</th>
                            <th class="text-white border-top-0 text-center">Lainnya</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($resign as $d)
                            <tr>
                                <td>{{ $d->kode_resign }}</td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-bold">{{ $d->nama_karyawan }}</span></td>
                                <td>{{ $d->nama_jabatan }}</td>
                                <td>{{ $d->kode_dept }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td class="text-center">{!! $d->pjp ? '<i class="ti ti-check text-success fs-5"></i>' : '<i class="ti ti-x text-danger fs-5"></i> ' !!}</td>
                                <td class="text-center">{!! $d->kasbon ? '<i class="ti ti-check text-success fs-5"></i>' : '<i class="ti ti-x text-danger fs-5"></i>' !!}</td>
                                <td class="text-center">{!! $d->piutang ? '<i class="ti ti-check text-success fs-5"></i>' : '<i class="ti ti-x text-danger fs-5"></i>' !!}</td>
                                <td><span class="badge bg-label-info">{{ $d->nama_kategori }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('resign.edit')
                                            <a href="#" class="btnEdit text-info" kode_resign="{{ Crypt::encrypt($d->kode_resign) }}" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('resign.show')
                                            <a href="{{ route('resign.cetak', Crypt::encrypt($d->kode_resign)) }}" class="text-primary" title="Cetak">
                                                <i class="ti ti-printer fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('resign.delete')
                                            <form method="POST" class="delete d-inline" action="{{ route('resign.delete', Crypt::encrypt($d->kode_resign)) }}">
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
                    {{ $resign->links() }}
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
            $(".modal-title").text("Input Resign");
            $("#loadmodal").load(`/resign/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_resign = $(this).attr("kode_resign");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Resign");
            $("#loadmodal").load(`/resign/${kode_resign}/edit`);
        });
    });
</script>
@endpush
