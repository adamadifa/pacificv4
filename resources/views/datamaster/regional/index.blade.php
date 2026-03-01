@extends('layouts.app')
@section('titlepage', 'Regional')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Regional</h4>
            <small class="text-muted">Mengelola pembagian wilayah regional.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-map me-1"></i>Regional</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('regional.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Regional" value="{{ Request('nama_regional') }}" name="nama_regional"
                        icon="ti ti-search" />
                </div>
                <div class="col-auto">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-map me-2"></i>Data Regional</h6>
                    @can('regional.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateRegional"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode Regional</th>
                            <th class="text-white">Nama Regional</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($regional as $d)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_regional }}</span></td>
                                <td>{{ $d->nama_regional }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('regional.edit')
                                            <a href="#" class="editRegional text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_regional="{{ Crypt::encrypt($d->kode_regional) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('regional.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('regional.delete', Crypt::encrypt($d->kode_regional)) }}">
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
                    {{-- {{ $regional->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateRegional" size="" show="loadcreateRegional" title="Tambah Regional" />
<x-modal-form id="mdleditRegional" size="" show="loadeditRegional" title="Edit Regional" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateRegional").click(function(e) {
            $('#mdlcreateRegional').modal("show");
            $("#loadcreateRegional").load('/regional/create');
        });

        $(".editRegional").click(function(e) {
            var kode_regional = $(this).attr("kode_regional");
            e.preventDefault();
            $('#mdleditRegional').modal("show");
            $("#loadeditRegional").load('/regional/' + kode_regional + '/edit');
        });
    });
</script>
@endpush
