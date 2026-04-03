@extends('layouts.app')
@section('titlepage', 'Users')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Users</h4>
            <small class="text-muted">Mengelola data pengguna dan hak akses sistem.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Settings</a>
                </li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    thead th {
        background-color: #002e65 !important;
    }
</style>

<div class="row">
    <div class="col-12">
        {{-- Filter Section --}}
        <form action="{{ route('users.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Cari User" value="{{ Request('name') }}" name="name" icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        selected="{{ Request('kode_dept') }}" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-select label="Role" name="role_id" :data="$roles" key="id" textShow="name"
                        upperCase="true" selected="{{ Request('role_id') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Data Users</h6>
                    <a href="#" class="btn btn-primary btn-sm" id="btncreateUser">
                        <i class="ti ti-plus me-1"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white">NO.</th>
                            <th class="text-white">USER</th>
                            <th class="text-white">EMAIL</th>
                            <th class="text-white">ROLE</th>
                            <th class="text-white">CABANG</th>
                            <th class="text-white">DEPT</th>
                            <th class="text-white">STATUS</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($users as $d)
                            <tr>
                                <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($d->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $d->name }}</h6>
                                            <small class="text-muted">{{ $d->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $d->email }}</td>
                                <td>
                                    @foreach ($d->roles as $role)
                                        <span class="badge bg-label-info">{{ ucwords($role->name) }}</span>
                                    @endforeach
                                </td>
                                <td>{{ textCamelCase($d->nama_cabang) }}</td>
                                <td><span class="badge bg-label-secondary">{{ textUpperCase($d->kode_dept) }}</span></td>
                                <td>
                                    @if ($d->status == 1)
                                        <span class="badge bg-label-success">
                                            <i class="ti ti-circle-filled fs-tiny me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger">
                                            <i class="ti ti-circle-filled fs-tiny me-1"></i> Non Aktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('users.createuserpermission', Crypt::encrypt($d->id)) }}" 
                                           class="text-info" data-bs-toggle="tooltip" title="Permissions">
                                            <i class="ti ti-shield-lock"></i>
                                        </a>
                                        <a href="#" class="pjpAccess text-primary" id="{{ Crypt::encrypt($d->id) }}"
                                           data-bs-toggle="tooltip" title="Akses PJP">
                                            <i class="ti ti-report-money"></i>
                                        </a>
                                        <a href="#" class="editUser text-success" id="{{ Crypt::encrypt($d->id) }}"
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('users.delete', Crypt::encrypt($d->id)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateUser" size="" show="loadcreateUser" title="Tambah User" />
<x-modal-form id="mdleditUser" size="" show="loadeditUser" title="Edit User" />
<x-modal-form id="mdlpjpAccess" size="" show="loadpjpAccess" title="Pengaturan Akses PJP" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateUser").click(function(e) {
            $('#mdlcreateUser').modal("show");
            $("#loadcreateUser").load('/users/create');
        });

        $(".editUser").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditUser').modal("show");
            $("#loadeditUser").load('/users/' + id + '/edit');
        });

        $(".pjpAccess").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdlpjpAccess').modal("show");
            $("#loadpjpAccess").load('/users/' + id + '/pjpaccess');
        });
    });
</script>
@endpush
