@extends('layouts.app')
@section('titlepage', 'Tujuan Angkutan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Tujuan Angkutan</h4>
            <small class="text-muted">Mengelola data tujuan dan tarif angkutan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-map-2 me-1"></i>Tujuan Angkutan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-lg-6 col-md-12 col-sm-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('tujuanangkutan.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon icon="ti ti-search" label="Cari Nama Tujuan" name="tujuan_search"
                        value="{{ Request('tujuan_search') }}" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-map-2 me-2"></i>Data Tujuan Angkutan</h6>
                    @can('tujuanangkutan.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Tujuan Angkutan
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
                            <th class="text-white" style="background-color: #002e65 !important;">TUJUAN</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">TARIF</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($tujuanangkutan as $d)
                            <tr>
                                <td>{{ $loop->iteration + $tujuanangkutan->firstItem() - 1 }}</td>
                                <td>{{ $d->kode_tujuan }}</td>
                                <td><span class="fw-semibold">{{ $d->tujuan }}</span></td>
                                <td class="text-end fw-bold">{{ formatAngka($d->tarif) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('tujuanangkutan.edit')
                                            <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_tujuan="{{ Crypt::encrypt($d->kode_tujuan) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('tujuanangkutan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('tujuanangkutan.delete', Crypt::encrypt($d->kode_tujuan)) }}">
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
                    {{ $tujuanangkutan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Tambah Tujuan Angkutan" />
<x-modal-form id="mdlEdit" size="" show="loadEdit" title="Edit Tujuan Angkutan" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/tujuanangkutan/create');
        });

        $(".btnEdit").click(function(e) {
            var kode_tujuan = $(this).attr("kode_tujuan");
            e.preventDefault();
            $('#mdlEdit').modal("show");
            $("#loadEdit").load('/tujuanangkutan/' + kode_tujuan + '/edit');
        });
    });
</script>
@endpush
