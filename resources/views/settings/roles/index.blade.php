@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Roles</h4>
            <small class="text-muted">Kelola data role dan permission pengguna.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Settings</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-shield-lock me-1"></i>Roles</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('roles.index') }}" id="formSearch">
            <div class="row g-2 mb-2">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Role" value="{{ Request('name') }}" name="name" icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-shield-lock me-2"></i>Data Roles</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateRole">
                            <i class="ti ti-plus me-1"></i> Tambah Role
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white" style="width: 5%;">No.</th>
                            <th class="text-white" style="width: 10%;">ID Role</th>
                            <th class="text-white">Role</th>
                            <th class="text-white">Guard</th>
                            <th class="text-white text-center" style="width: 15%;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($roles as $d)
                            <tr>
                                <td>{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
                                <td><span class="badge bg-label-secondary font-monospace">{{ $d->id }}</span></td>
                                <td><span class="fw-bold">{{ ucwords($d->name) }}</span></td>
                                <td>{{ $d->guard_name }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('roles.createrolepermission', Crypt::encrypt($d->id)) }}"
                                            class="text-info" data-bs-toggle="tooltip" title="Permission">
                                            <i class="ti ti-shield-lock fs-5"></i>
                                        </a>
                                        <a href="#" class="text-success editRole" id="{{ $d->id }}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="ti ti-edit fs-5"></i>
                                        </a>
                                        <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('roles.delete', Crypt::encrypt($d->id)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
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
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateRole" size="" show="loadcreateRole" title="Tambah Role" />
<x-modal-form id="mdleditRole" size="" show="loadeditRole" title="Edit Role" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateRole").click(function(e) {
            $('#mdlcreateRole').modal("show");
            $("#loadcreateRole").load('/roles/create');
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/roles/' + id + '/edit');
        });
    });
</script>
@endpush
