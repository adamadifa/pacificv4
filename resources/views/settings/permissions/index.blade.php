@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
@section('navigasi')
    <span>Permissions</span>
@endsection
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4">
                <div>
                    <h5 class="card-title mb-0 fw-bold text-primary">Manajemen Permission</h5>
                    <small class="text-muted">Kelola hak akses sistem, role, dan user secara dinamis</small>
                </div>
                <a href="#" class="btn btn-primary" id="btncreatePermission">
                    <i class="fa fa-plus me-2"></i> Tambah Permission
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('permissions.index') }}">
                            <div class="row align-items-end mb-3">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <x-select name="id_permission_group" label="Filter Group Permission" :data="$permission_groups" key="id"
                                        textShow="name" selected="{{ Request('id_permission_group') }}" select2="select2Group" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3 d-flex gap-2">
                                    <button class="btn btn-primary flex-grow-1"><i class="fa fa-filter me-1"></i> Filter</button>
                                    @if(Request('id_permission_group'))
                                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary"><i class="fa fa-sync"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" id="tableSearch" class="form-control" placeholder="Cari nama permission di halaman ini...">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive border rounded mb-2" style="max-height: 60vh; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="py-3" style="width: 80px;">No.</th>
                                        <th class="py-3">Permission Name</th>
                                        <th class="py-3">Group</th>
                                        <th class="py-3 text-end pe-4" style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="permissionTableBody">
                                    @forelse ($permissions as $d)
                                        <tr>
                                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-label-primary fs-7 text-lowercase">{{ $d->name }}</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary fw-medium">{{ $d->group_name }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-inline-flex gap-2">
                                                    <button class="btn btn-sm btn-icon btn-label-info detailPermission"
                                                        id="{{ Crypt::encrypt($d->id) }}" title="Detail & Kelola Akses">
                                                        <i class="fa fa-info-circle"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-icon btn-label-success editPermission"
                                                        id="{{ Crypt::encrypt($d->id) }}" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <form method="POST" name="deleteform" class="deleteform d-inline-block"
                                                        action="{{ route('permissions.delete', Crypt::encrypt($d->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon btn-label-danger delete-confirm" title="Hapus">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Tidak ada data permission ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreatePermission" size="" show="loadcreatePermission" title="Tambah Permission" />
<x-modal-form id="mdleditPermission" size="" show="loadeditPermission" title="Edit Permission" />
<x-modal-form id="mdldetailPermission" size="modal-lg" show="loaddetailPermission" title="Detail Permission" />
@endsection
@push('myscript')
<script>
    $(function() {
        // Initialize Select2
        const select2Group = $('.select2Group');
        if (select2Group.length) {
            select2Group.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Filter Group',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

        // Live Table Search
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#permissionTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $("#btncreatePermission").click(function(e) {
            $('#mdlcreatePermission').modal("show");
            $("#loadcreatePermission").load('/permissions/create');
        });

        $(".editPermission").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditPermission').modal("show");
            $("#loadeditPermission").load('/permissions/' + id + '/edit');
        });

        $(".detailPermission").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdldetailPermission').modal("show");
            $("#loaddetailPermission").load('/permissions/' + id + '/show');
        });
    });
</script>
@endpush
