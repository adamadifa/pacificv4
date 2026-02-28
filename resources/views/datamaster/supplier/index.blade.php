@extends('layouts.app')
@section('titlepage', 'Supplier')

@section('style')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Supplier</h4>
            <small class="text-muted">Mengelola daftar pemasok dan mitra bisnis.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-building-store me-1"></i>Supplier</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-2 mt-2">
    <div class="col-12 text-end">
        @can('supplier.create')
            <a href="#" class="btn btn-primary btn-sm" id="btncreateSupplier"><i class="ti ti-plus me-1"></i> Tambah</a>
        @endcan
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        {{-- Filter Section --}}
        <form action="{{ route('supplier.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Nama Supplier" value="{{ Request('nama_supplier') }}" name="nama_supplier" icon="ti ti-search" />
                </div>

                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @foreach ($supplier as $d)
        <div class="col-12 mb-2">
            <div class="card shadow-none border">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <!-- Bagian 1: Identitas (Left) -->
                        <div class="col-md-3 border-end">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar avatar-md">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ substr($d->nama_supplier, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-0 fw-bold text-dark line-clamp-2" style="font-size: 0.85rem; line-height: 1.2;">
                                        {{ textupperCase($d->nama_supplier) }}
                                    </div>
                                    <span class="text-primary small fw-semibold" style="font-size: 0.75rem;">
                                        {{ $d->kode_supplier }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Informasi Alamat & CP (Center) -->
                        <div class="col-md-6 border-end">
                            <div class="row g-0">
                                <div class="col-md-7 pe-2">
                                    <div class="text-muted d-flex align-items-center mb-0" style="font-size: 0.7rem;">
                                        <i class="ti ti-map-pin me-1" style="font-size: 0.8rem;"></i> Alamat
                                    </div>
                                    <div class="text-dark line-clamp-2" style="font-size: 0.8rem; line-height: 1.3;">
                                        {{ textCamelCase($d->alamat_supplier) }}
                                    </div>
                                </div>
                                <div class="col-md-5 pe-2">
                                    <div class="text-muted d-flex align-items-center mb-0" style="font-size: 0.7rem;">
                                        <i class="ti ti-user me-1" style="font-size: 0.8rem;"></i> CP
                                    </div>
                                    <div class="text-dark fw-semibold line-clamp-2" style="font-size: 0.8rem; line-height: 1.3;">
                                        {!! strip_tags($d->contact_person) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Kontak & Action (Right) -->
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="pe-2">
                                    <div class="d-flex align-items-center mb-0 line-clamp-2" style="font-size: 0.75rem;">
                                        <i class="ti ti-phone me-1 text-success" style="font-size: 0.8rem;"></i> {{ $d->no_hp_supplier ?: '-' }}
                                    </div>
                                    <div class="d-flex align-items-center mb-0 line-clamp-2" style="font-size: 0.75rem;">
                                        <i class="ti ti-credit-card me-1 text-warning" style="font-size: 0.8rem;"></i>
                                        {{ $d->no_rekening_supplier ?: '-' }}
                                    </div>
                                </div>

                                <div class="d-flex gap-1 ms-auto">
                                    @can('supplier.edit')
                                        <a href="#" class="editSupplier btn btn-xs btn-icon btn-label-primary" data-bs-toggle="tooltip"
                                            title="Edit" kode_supplier="{{ Crypt::encrypt($d->kode_supplier) }}">
                                            <i class="ti ti-pencil" style="font-size: 1rem;"></i>
                                        </a>
                                    @endcan

                                    @can('supplier.delete')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('supplier.delete', Crypt::encrypt($d->kode_supplier)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm btn btn-xs btn-icon btn-label-danger"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash" style="font-size: 1rem;"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row mt-2">
    <div class="col-12">
        <div style="float: right;">
            {{ $supplier->links() }}
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateSupplier" size="" show="loadcreateSupplier" title="Tambah Supplier" />
<x-modal-form id="mdleditSupplier" size="" show="loadeditSupplier" title="Edit Supplier" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateSupplier").click(function(e) {
            $('#mdlcreateSupplier').modal("show");
            $("#loadcreateSupplier").load('/supplier/create');
        });

        $(".editSupplier").click(function(e) {
            var kode_supplier = $(this).attr("kode_supplier");
            e.preventDefault();
            $('#mdleditSupplier').modal("show");
            $("#loadeditSupplier").load('/supplier/' + kode_supplier + '/edit');
        });
    });
</script>
@endpush
