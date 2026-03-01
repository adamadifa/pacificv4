@extends('layouts.app')
@section('titlepage', 'Pembayaran PJP')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Pembayaran PJP</h4>
            <small class="text-muted">Manajemen pemotongan gaji dan pembayaran PJP karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report-money me-1"></i>Pembayaran PJP</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }
</style>

<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_pjp')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('pembayaranpjp.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                            value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option
                                            @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                            @else
                                            {{ date('Y') == $t ? 'selected' : '' }} @endif
                                            value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 text-end">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Data Pembayaran PJP</h6>
                    @can('pembayaranpjp.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Pembayaran PJP
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">BULAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TAHUN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historibayar as $d)
                            <tr>
                                <td class="text-center fw-bold">{{ $d->kode_potongan }}</td>
                                <td class="text-center">{{ $namabulan[$d->bulan] }}</td>
                                <td class="text-center">{{ $d->tahun }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatRupiah($d->totalpembayaran) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('pembayaranpjp.show')
                                            <a href="#" class="btnShow text-info"
                                                kode_potongan="{{ Crypt::encrypt($d->kode_potongan) }}" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('pembayaranpjp.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('pembayaranpjp.deletegenerate', Crypt::encrypt($d->kode_potongan)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
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
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
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
            $("#modal").find(".modal-title").text('Generate Pembayaran');
            $("#modal").find("#loadmodal").load(`pembayaranpjp/create`);
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });


        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_potongan = $(this).attr('kode_potongan');
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Detail Pembayaran PJP');
            $("#modal").find(".modal-dialog").addClass("modal-xl");
            $("#modal").find("#loadmodal").load(`/pembayaranpjp/${kode_potongan}/false/show`);

        });
    });
</script>
@endpush
