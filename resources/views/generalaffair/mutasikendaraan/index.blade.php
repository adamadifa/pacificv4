@extends('layouts.app')
@section('titlepage', 'Mutasi Kendaraan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Mutasi Kendaraan</h4>
            <small class="text-muted">Mengelola data mutasi kendaraan antar cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>General Affair</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-truck me-1"></i>Mutasi Kendaraan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('mutasikendaraan.index') }}">
            <div class="row g-2 mb-2 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari No. Polisi" value="{{ Request('no_polisi') }}" name="no_polisi" icon="ti ti-search" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-2">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Data Mutasi Kendaraan</h6>
                    @can('mutasikendaraan.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Mutasi</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. MUTASI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. POLISI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TGL MUTASI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">ASAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TUJUAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KETERANGAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($mutasikendaraan as $k)
                            <tr>
                                <td><span class="fw-bold">{{ $k->no_mutasi }}</span></td>
                                <td>{{ $k->no_polisi }}</td>
                                <td>{{ formatIndo($k->tanggal) }}</td>
                                <td>{{ $k->cabang_asal }}</td>
                                <td>{{ $k->cabang_tujuan }}</td>
                                <td>{{ $k->keterangan }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        @can('mutasikendaraan.delete')
                                            <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('mutasikendaraan.delete', Crypt::encrypt($k->no_mutasi)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </a>
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
                    {{ $mutasikendaraan->links() }}
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
        const loading = () => {
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
            $(".modal-title").text("Input Mutasi Kendaraan");
            $("#loadmodal").load(`/mutasikendaraan/create`);
        });
    });
</script>
@endpush
