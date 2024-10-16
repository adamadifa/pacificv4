@extends('layouts.app')
@section('titlepage', 'Lembur')

@section('content')
@section('navigasi')
    <span>Lembur</span>
@endsection
<div class="row">
    <div class="col-lg-9 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('lembur.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Buat Lembur</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('lembur.index') }}" method="GET">
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
                            <div class="form-group mb-3">
                                <select name="kategori" id="kategori" class="form-select">
                                    <option value="">Kategori Lembur</option>
                                    <option value="1" @if (Request('kategori') == 1) selected @endif>Lembur Reguler</option>
                                    <option value="2" @if (Request('kategori') == 2) selected @endif>Lembur Hari Libur</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                                        upperCase="true" selected="{{ Request('kode_dept') }}" />
                                </div>
                            </div>
                            @if (!empty($listApprovepenilaian))
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-grou mb-3">
                                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                                <option value="">Poisi Ajuan</option>
                                                @foreach ($listApprovepenilaian as $d)
                                                    <option value="{{ $d }}" {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                                        {{ textUpperCase($d) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 co-sm-12 col-md-12">
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
                            @else
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
                            @endif
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
                                        <th>Cabang</th>
                                        <th>Dept.</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Istirahat</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lembur as $l)
                                        @php

                                            $roles_approve = cekRoleapprovelembur($l->kode_dept);
                                            $end_role = end($roles_approve);
                                            if ($level_user != $end_role) {
                                                $index_role = array_search($level_user, $roles_approve);
                                                $next_role = $roles_approve[$index_role + 1];
                                            } else {
                                                $lastindex = count($roles_approve) - 1;
                                                $next_role = $roles_approve[$lastindex];
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{ $l->kode_lembur }}</td>
                                            <td>{{ formatIndo($l->tanggal) }}</td>
                                            <td>{{ textUpperCase($l->nama_cabang) }}</td>
                                            <td>{{ $l->kode_dept }}</td>
                                            <td>
                                                @if ($l->kategori == 1)
                                                    <span class="badge bg-success">Lembur Reguler</span>
                                                @else
                                                    <span class="badge bg-primary">Lembur Haril Libur</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($l->istirahat == 1)
                                                    <i class="ti ti-square-rounded-check text-success"></i>
                                                @else
                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($l->posisi_ajuan))
                                                    <span
                                                        class="badge bg-primary">{{ singkatString($l->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($l->posisi_ajuan) }}
                                                    </span>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if ($l->status == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">

                                                    @can('lembur.edit')
                                                        @if ($l->status === '0')
                                                            <a href="#" kode_lembur="{{ Crypt::encrypt($l->kode_lembur) }}" class="btnEdit me-1">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('lembur.setlembur')
                                                        <a href="{{ route('lembur.aturlembur', Crypt::encrypt($l->kode_lembur)) }}" class="me-1">
                                                            <i class="ti ti-settings-cog text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('lembur.approve')
                                                        @if ($level_user == $l->posisi_ajuan && $l->status === '0')
                                                            <a href="#" class="btnApprove me-1"
                                                                kode_lembur="{{ Crypt::encrypt($l->kode_lembur) }}">
                                                                <i class="ti ti-external-link text-success"></i>
                                                            </a>
                                                        @elseif ($l->posisi_ajuan == $next_role && $l->status === '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('lembur.cancel', Crypt::encrypt($l->kode_lembur)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @elseif ($level_user == $l->posisi_ajuan && $l->status === '1')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('lembur.cancel', Crypt::encrypt($l->kode_lembur)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('lembur.delete')
                                                        @if ($l->status === '0' || $level_user == 'asst. manager hrd' || $level_user == 'super admin')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('lembur.delete', Crypt::encrypt($l->kode_lembur)) }}">
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
                            {{ $lembur->links() }}
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
            $(".modal-title").text("Buat Lembur");
            $("#loadmodal").load(`/lembur/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_lembur = $(this).attr("kode_lembur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Lembur");
            $("#loadmodal").load(`/lembur/${kode_lembur}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            var kode_lembur = $(this).attr("kode_lembur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Lembur");
            $("#loadmodal").load(`/lembur/${kode_lembur}/approve`);
        });
    });
</script>
@endpush
