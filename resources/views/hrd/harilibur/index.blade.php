@extends('layouts.app')
@section('titlepage', 'Hari Libur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Hari Libur</h4>
            <small class="text-muted">Mengelola daftar hari libur dan cuti bersama.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-calendar me-1"></i>Hari Libur</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('harilibur.index') }}" method="GET">
            <div class="row g-2 mb-1 align-items-end">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date"
                        :value="Request('dari')" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date"
                        :value="Request('sampai')" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <x-select label="Kategori" name="kategori" :data="$kategorilibur" key="kode_kategori" textShow="nama_kategori"
                        :selected="Request('kategori')" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="status" id="status" class="form-select">
                            <option value="">Status</option>
                            <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        </select>
                    </div>
                </div>
            </div>
            @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur']))
                <div class="row g-2 mb-1 align-items-end">
                    <div class="col">
                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            upperCase="true" select2="select2Kodecabang" :selected="Request('kode_cabang')" hideLabel="true" />
                    </div>
                    <div class="col">
                        <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                            upperCase="true" select2="select2KodeDept" :selected="Request('kode_dept')" hideLabel="true" />
                    </div>
                    <div class="col-auto">
                        <div class="form-group mb-1">
                            <button class="btn btn-primary" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-1 text-end">
                            <button class="btn btn-primary px-4" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                        </div>
                    </div>
                </div>
            @endif
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar me-2"></i>Data Hari Libur</h6>
                    @can('harilibur.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">Kode</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Pengganti</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Dept</th>
                            <th class="text-white">Kategori</th>
                            <th class="text-white" style="width: 30%">Keterangan</th>
                            <th class="text-white text-center">HRD</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($harilibur as $d)
                            <tr>
                                <td><span class="fw-semibold">{{ $d->kode_libur }}</span></td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ formatIndo($d->tanggal_diganti) }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td>{!! $d->kode_dept != null ? $d->kode_dept : '<span class="badge bg-success">All</span></span>' !!}</td>
                                <td>
                                    <span class="badge bg-{{ $d->color }}">
                                        {{ $d->nama_kategori }}
                                    </span>
                                </td>
                                <td>{{ textcamelCase($d->keterangan) }}</td>
                                <td class="text-center">
                                    @if ($d->status == '1')
                                        <i class="ti ti-checks text-success"></i>
                                    @else
                                        <i class="ti ti-hourglass-low text-warning"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('harilibur.edit')
                                            @if ($d->status === '0')
                                                <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                    kode_libur="{{ Crypt::encrypt($d->kode_libur) }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('harilibur.setharilibur')
                                            <a href="{{ route('harilibur.aturharilibur', Crypt::encrypt($d->kode_libur)) }}"
                                                class="text-info" data-bs-toggle="tooltip" title="Atur Hari Libur">
                                                <i class="ti ti-settings-cog"></i>
                                            </a>
                                        @endcan
                                        @can('harilibur.approve')
                                            @if ($d->status === '0')
                                                <a href="#" class="btnApprove text-info" data-bs-toggle="tooltip" title="Approve"
                                                    kode_libur="{{ Crypt::encrypt($d->kode_libur) }}">
                                                    <i class="ti ti-external-link"></i>
                                                </a>
                                            @else
                                                <form action="{{ route('harilibur.cancel', Crypt::encrypt($d->kode_libur)) }}"
                                                    method="POST" id="formApprove" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm text-warning" data-bs-toggle="tooltip"
                                                        title="Batalkan Approve">
                                                        <i class="ti ti-square-rounded-x"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('harilibur.delete')
                                            @if ($d->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('harilibur.delete', Crypt::encrypt($d->kode_libur)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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
                    {{ $harilibur->links() }}
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
        const select2Kodecabang = $(".select2Kodecabang");
        if (select2Kodecabang.length > 0) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeDept = $(".select2KodeDept");
        if (select2KodeDept.length > 0) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

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
            $(".modal-title").text("Buat Hari Libur");
            $("#loadmodal").load(`/harilibur/create`);
            $("#modal").find(".modal-dialog").removeClass("modal-lg");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr("kode_libur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Hari Libur");
            $("#loadmodal").load(`/harilibur/${kode_libur}/edit`);
            $("#modal").find(".modal-dialog").removeClass("modal-lg");
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr("kode_libur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Hari Libur");
            $("#loadmodal").load(`/harilibur/${kode_libur}/approve`);
            $("#modal").find(".modal-dialog").addClass("modal-lg");
        });
    });
</script>
@endpush
