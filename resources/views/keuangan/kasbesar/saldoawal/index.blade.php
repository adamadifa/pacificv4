@extends('layouts.app')
@section('titlepage', 'Saldo Awal Kas Besar')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Saldo Awal Kas Besar</h4>
            <small class="text-muted">Manajemen saldo awal kas besar per periode.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-wallet me-1"></i>Saldo Awal Kas Besar</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('sakasbesar.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                            value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">Tahun</label>
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
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Data Saldo Awal</h6>
                    @can('sakasbesar.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Buat Saldo Awal
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">BULAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TAHUN</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">KERTAS</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">LOGAM</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">TRANSFER</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">GIRO</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($saldo_awal as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->kode_saldo_awal }}</span></td>
                                <td><span class="badge bg-label-primary">{{ textUpperCase($d->nama_cabang) }}</span></td>
                                <td>{{ $nama_bulan[$d->bulan] }}</td>
                                <td>{{ $d->tahun }}</td>
                                <td class="text-end">{{ formatAngka($d->uang_kertas) }}</td>
                                <td class="text-end">{{ formatAngka($d->uang_logam) }}</td>
                                <td class="text-end">{{ formatAngka($d->transfer) }}</td>
                                <td class="text-end">{{ formatAngka($d->giro) }}</td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('sakasbesar.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('sakasbesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-4"></i>
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

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Saldo Awal Kas Besar");
            $("#loadmodal").load(`/sakasbesar/create`);
        });
    });
</script>
@endpush
