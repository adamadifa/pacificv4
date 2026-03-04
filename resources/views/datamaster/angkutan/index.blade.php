@extends('layouts.app')
@section('titlepage', 'Angkutan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Angkutan</h4>
            <small class="text-muted">Mengelola data angkutan atau ekspedisi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-truck me-1"></i>Angkutan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-lg-6 col-md-12 col-sm-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('angkutan.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon icon="ti ti-search" label="Cari Nama Angkutan" name="nama_angkutan_search"
                        value="{{ Request('nama_angkutan_search') }}" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Data Angkutan</h6>
                    @can('angkutan.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Angkutan
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO.</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NAMA ANGKUTAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($angkutan as $d)
                            <tr>
                                <td>{{ $loop->iteration + $angkutan->firstItem() - 1 }}</td>
                                <td>{{ $d->kode_angkutan }}</td>
                                <td><span class="fw-semibold">{{ $d->nama_angkutan }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('angkutan.edit')
                                            <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_angkutan="{{ Crypt::encrypt($d->kode_angkutan) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('angkutan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('angkutan.delete', Crypt::encrypt($d->kode_angkutan)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
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
                    {{ $angkutan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Tambah Angkutan" />
<x-modal-form id="mdlEdit" size="" show="loadEdit" title="Edit Angkutan" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/angkutan/create');
        });

        $(".btnEdit").click(function(e) {
            var kode_angkutan = $(this).attr("kode_angkutan");
            e.preventDefault();
            $('#mdlEdit').modal("show");
            $("#loadEdit").load('/angkutan/' + kode_angkutan + '/edit');
        });
    });
</script>
@endpush
