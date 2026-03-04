@extends('layouts.app')
@section('titlepage', 'Salesman')

@section('content')
@section('navigasi')
    <span>Salesman</span>
@endsection
<div class="row">
    <div class="col-12">
        <div class="row mb-2">
            <div class="col-12">
                @can('salesman.create')
                    <a href="#" class="btn btn-primary" id="btncreateSalesman"><i class="fa fa-plus me-2"></i> Tambah
                        Salesman</a>
                @endcan
            </div>
        </div>
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('salesman.index') }}">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Cari Nama Salesman" value="{{ Request('nama_salesman') }}"
                        name="nama_salesman" icon="ti ti-search" hideLabel="true" />
                </div>
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                    </div>
                @endhasanyrole
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i
                                class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data List --}}
        <div class="row">
            @foreach ($salesman as $d)
                <div class="col-12 mb-3">
                    <div class="card shadow-none border">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <!-- Bagian 1: Identitas (Left) -->
                                <div class="col-md-5 border-end">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            @if (!empty($d->marker))
                                                <div class="avatar avatar-md online">
                                                    <img src="{{ getdocMarker($d->marker) }}" alt="Marker"
                                                        class="rounded-circle shadow-sm border">
                                                </div>
                                            @else
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-initial rounded-circle bg-label-info shadow-sm">
                                                        <i class="ti ti-user ti-sm"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="mb-1 fw-bold text-dark" style="font-size: 0.95rem;">
                                                {{ textCamelCase($d->nama_salesman) }}
                                                <span class="text-muted fw-normal" style="font-size: 0.8rem;">({{ $d->kode_salesman }})</span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge border text-primary bg-label-primary" style="font-size: 0.65rem;">
                                                    <i class="ti ti-category me-1" style="font-size: 0.75rem;"></i>{{ $d->nama_kategori_salesman }}
                                                </span>
                                                @if ($d->status_aktif_salesman == 1)
                                                    <span class="badge border text-success bg-label-success" style="font-size: 0.65rem;">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="badge border text-danger bg-label-danger" style="font-size: 0.65rem;">
                                                        Non-Aktif
                                                    </span>
                                                @endif
                                                <span class="badge border text-secondary bg-label-secondary" style="font-size: 0.65rem;">
                                                    <i class="ti ti-map-pin me-1" style="font-size: 0.75rem;"></i>{{ $d->kode_cabang }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 2: Info (Center) -->
                                <div class="col-md-4 border-end">
                                    <div class="ps-md-2">
                                        <div class="d-flex align-items-start mb-1 text-muted">
                                            <i class="ti ti-direction me-2 mt-1" style="font-size: 0.9rem;"></i>
                                            <span class="text-truncate-2" style="font-size: 0.75rem; line-height: 1.2;">{{ textCamelCase($d->alamat_salesman) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3 mt-1">
                                            <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                                <i class="ti ti-phone me-1 text-info" style="font-size: 0.85rem;"></i>
                                                {{ $d->no_hp_salesman }}
                                            </div>
                                            <div class="d-flex align-items-center" style="font-size: 0.75rem;">
                                                <span class="text-muted me-1">Komisi:</span>
                                                @if ($d->status_komisi_salesman == 1)
                                                    <i class="ti ti-circle-check text-success" style="font-size: 0.9rem;"></i>
                                                @else
                                                    <i class="ti ti-circle-x text-danger" style="font-size: 0.9rem;"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 3: Actions (Right) -->
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-end align-items-center h-100 pe-2">
                                        <div class="btn-group shadow-sm">
                                            @can('salesman.edit')
                                                <a href="#" class="btn btn-icon btn-outline-primary editSalesman"
                                                    kode_salesman="{{ Crypt::encrypt($d->kode_salesman) }}" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('salesman.delete')
                                                <form method="POST" name="deleteform" class="deleteform m-0"
                                                    action="{{ route('salesman.delete', Crypt::encrypt($d->kode_salesman)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-outline-danger delete-confirm"
                                                        style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"
                                                        title="Delete">
                                                        <i class="ti ti-trash"></i>
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

        {{-- Pagination --}}
        <div class="row">
            <div class="col-12 mt-2">
                <div style="float: right;">
                    {{ $salesman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateSalesman" size="" show="loadcreateSalesman" title="Tambah Salesman" />
<x-modal-form id="mdleditSalesman" size="" show="loadeditSalesman" title="Edit Salesman" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateSalesman").click(function(e) {
            $('#mdlcreateSalesman').modal("show");
            $("#loadcreateSalesman").load('/salesman/create');
        });

        $(".editSalesman").click(function(e) {
            var kode_salesman = $(this).attr("kode_salesman");
            e.preventDefault();
            $('#mdleditSalesman').modal("show");
            $("#loadeditSalesman").load('/salesman/' + kode_salesman + '/edit');
        });
    });
</script>
@endpush
