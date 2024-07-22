@extends('layouts.app')
@section('titlepage', 'Hari Libur')

@section('content')
@section('navigasi')
    <span>Hari Libur</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('harilibur.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Tambah Hari Libur</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('harilibur.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date"
                                        :value="Request('dari')" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date"
                                        :value="Request('sampai')" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-select label="Kategori" name="kategori" :data="$kategorilibur" key="kode_kategori" textShow="nama_kategori"
                                        :selected="Request('kategori')" />
                                </div>
                            </div>
                            @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur']))
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                            upperCase="true" select2="select2Kodecabang" :selected="Request('kode_cabang')" />
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                                            upperCase="true" select2="select2KodeDept" :selected="Request('kode_dept')" />
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Status</option>
                                            <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>
                                                Disetujui</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Pengganti</th>
                                        <th>Cabang</th>
                                        <th>Dept</th>
                                        <th>Kategori</th>
                                        <th style="width: 30%">Keterangan</th>
                                        <th>HRD</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($harilibur as $d)
                                        <tr>
                                            <td>{{ $d->kode_libur }}</td>
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
                                                <div class="d-flex">
                                                    @can('harilibur.edit')
                                                        @if ($d->status === '0')
                                                            <a href="#" class="btnEdit me-1" kode_libur="{{ Crypt::encrypt($d->kode_libur) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('harilibur.setharilibur')
                                                        <a href="{{ route('harilibur.aturharilibur', Crypt::encrypt($d->kode_libur)) }}" class="me-1">
                                                            <i class="ti ti-settings-cog text-info"></i>
                                                        </a>
                                                    @endcan
                                                    @can('harilibur.approve')
                                                        @if ($d->status === '0')
                                                            <a href="#" class="btnApprove me-1" kode_libur="{{ Crypt::encrypt($d->kode_libur) }}">
                                                                <i class="ti ti-external-link"></i>
                                                            </a>
                                                        @else
                                                            <form action="{{ route('harilibur.cancel', Crypt::encrypt($d->kode_libur)) }}" method="POST"
                                                                id="formApprove">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('harilibur.delete')
                                                        @if ($d->status === '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('harilibur.delete', Crypt::encrypt($d->kode_libur)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
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
                        <div style="float: right;">
                            {{ $harilibur->links() }}
                        </div>
                    </div>
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
