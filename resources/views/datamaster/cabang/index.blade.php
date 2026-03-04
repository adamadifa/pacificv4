@extends('layouts.app')
@section('titlepage', 'Cabang')

@section('content')
@section('navigasi')
    <span>Cabang</span>
@endsection
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mb-3">
            <div class="card-body py-3">
                <form action="{{ route('cabang.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <x-input-with-icon label="Cari Nama Cabang" value="{{ Request('nama_cabang') }}" name="nama_cabang"
                                icon="ti ti-search" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach ($cabang as $d)
                <div class="col-12 mb-3">
                    <div class="card shadow-none border">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <!-- Bagian 1: Identitas (Left) -->
                                <div class="col-md-5 border-end">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-md">
                                                <span class="avatar-initial rounded-circle bg-label-primary shadow-sm">
                                                    <i class="ti ti-building ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="mb-1 fw-bold text-dark" style="font-size: 0.95rem;">
                                                {{ textUpperCase($d->nama_cabang) }}
                                                <span class="text-muted fw-normal" style="font-size: 0.8rem;">({{ $d->kode_cabang }})</span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge border text-primary bg-label-primary" style="font-size: 0.65rem;">
                                                    <i class="ti ti-topology-ring-3 me-1" style="font-size: 0.75rem;"></i>{{ $d->nama_regional }}
                                                </span>
                                                <span class="badge border text-info bg-label-info" style="font-size: 0.65rem;">
                                                    <i class="ti ti-building-skyscraper me-1" style="font-size: 0.75rem;"></i>{{ $d->nama_pt }} ({{ $d->kode_pt }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 2: Info (Center) -->
                                <div class="col-md-4 border-end">
                                    <div class="ps-md-2">
                                        <div class="d-flex align-items-start mb-1">
                                            <i class="ti ti-map-pin me-2 mt-1 text-muted" style="font-size: 0.9rem;"></i>
                                            <span class="text-muted text-truncate-2" style="font-size: 0.75rem; line-height: 1.2;">{{ $d->alamat_cabang }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                                <i class="ti ti-phone me-1 text-info" style="font-size: 0.85rem;"></i>
                                                {{ $d->telepon_cabang }}
                                            </div>
                                            <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                                <i class="ti ti-access-point me-1 text-warning" style="font-size: 0.85rem;"></i>
                                                {{ $d->radius_cabang }}m
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 3: Actions (Right) -->
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ms-3 d-flex flex-column align-items-center">
                                            <span class="badge rounded-pill p-2 mb-1 shadow-sm" style="background-color:{{ $d->color_marker }}; min-width: 40px;">
                                                <i class="ti ti-point text-white"></i>
                                            </span>
                                            <small class="text-muted" style="font-size: 0.65rem;">Marker</small>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="btn-group shadow-sm">
                                                @can('cabang.edit')
                                                    <a href="#" class="btn btn-icon btn-outline-primary editCabang"
                                                        kode_cabang="{{ Crypt::encrypt($d->kode_cabang) }}" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('cabang.delete')
                                                    <form method="POST" name="deleteform" class="deleteform m-0"
                                                        action="{{ route('cabang.delete', Crypt::encrypt($d->kode_cabang)) }}">
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
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 mt-2">
                <div style="float: right;">
                    {{ $cabang->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateCabang" size="" show="loadcreateCabang" title="Tambah Cabang" />
<x-modal-form id="mdleditCabang" size="" show="loadeditCabang" title="Edit Cabang" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateCabang").click(function(e) {
            $('#mdlcreateCabang').modal("show");
            $("#loadcreateCabang").load('/cabang/create');
        });

        $(".editCabang").click(function(e) {
            var kode_cabang = $(this).attr("kode_cabang");
            e.preventDefault();
            $('#mdleditCabang').modal("show");
            $("#loadeditCabang").load('/cabang/' + kode_cabang + '/edit');
        });
    });
</script>
@endpush
