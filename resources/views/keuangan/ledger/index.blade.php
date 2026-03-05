@extends('layouts.app')
@section('titlepage', 'Ledger')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ledger</h4>
            <small class="text-muted">Manajemen transaksi ledger bank.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-book me-1"></i>Ledger</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }

    .col-keterangan {
        width: 25% !important;
        white-space: normal !important;
        min-width: 200px !important;
    }

    .col-kode-akun {
        width: 20% !important;
        white-space: normal !important;
        min-width: 150px !important;
    }

    .table {
        font-size: 14px !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_ledger')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('ledger.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_bank_search" id="kode_bank_search" class="form-select select2Kodebanksearch">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($bank as $d)
                                        <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}
                                            value="{{ $d->kode_bank }}">{{ $d->nama_bank }} ({{ $d->no_rekening }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Data Ledger Bank</h6>
                    @can('ledger.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Ledger
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">PELANGGAN</th>
                            <th class="text-white text-center col-keterangan" style="background-color: #002e65 !important;">KETERANGAN</th>
                            <th class="text-white text-center col-kode-akun" style="background-color: #002e65 !important;">KODE AKUN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">PRT</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">DEBET</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KREDIT</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">SALDO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">#</th>
                        </tr>
                        <tr style="background-color: #f1f1f1;">
                            <th colspan="7" class="fw-bold">SALDO AWAL</th>
                            <td class="text-end fw-bold {{ $saldo_awal == null ? 'bg-danger text-white' : 'text-white' }}">
                                @if ($saldo_awal != null)
                                    {{ formatAngka($saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit) }}
                                @else
                                    BELUM DI SET
                                @endif
                            </td>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $saldo = $saldo_awal != null ? $saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit : 0;
                            $total_debet = 0;
                            $total_kredit = 0;
                        @endphp
                        @foreach ($ledger as $d)
                            @php
                                $color_cr = !empty($d->kode_cr) ? 'table-primary fw-semibold' : '';
                                $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                $saldo = $saldo - $debet + $kredit;

                                $total_debet += $debet;
                                $total_kredit += $kredit;
                            @endphp
                            <tr class="{{ $color_cr }}">
                                <td class="text-center">{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                <td>{{ textCamelCase($d->pelanggan) }}</td>
                                <td class="col-keterangan">{{ textCamelCase($d->keterangan) }}</td>
                                <td class="col-kode-akun"><span class="badge bg-label-primary">{{ $d->kode_akun }}</span> {{ $d->nama_akun }}</td>
                                <td class="text-center">{{ $d->kode_peruntukan == 'MP' ? $d->kode_peruntukan : $d->keterangan_peruntukan }}</td>
                                <td class="text-end text-danger">{{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '' }} </td>
                                <td class="text-end text-success">{{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '' }} </td>
                                <td class="text-end fw-bold">{{ formatAngka($saldo) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        @can('ledger.edit')
                                            <a href="#" class="btnEdit me-2" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit text-success fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('ledger.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('ledger.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </a>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="5" class="fw-bold text-center" style="background-color: #002e65 !important;">TOTAL</td>
                            <td class="text-end fw-bold" style="background-color: #002e65 !important;">{{ formatAngka($total_debet) }}</td>
                            <td class="text-end fw-bold" style="background-color: #002e65 !important;">{{ formatAngka($total_kredit) }}</td>
                            <td class="text-end fw-bold" style="background-color: #002e65 !important;">{{ formatAngka($saldo) }}</td>
                            <td style="background-color: #002e65 !important;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalEdit" show="loadmodalEdit" title="" />

@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal,#loadmodalEdit").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        const select2Kodebanksearch = $('.select2Kodebanksearch');
        if (select2Kodebanksearch.length) {
            select2Kodebanksearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Ledger');
            $("#loadmodal").load('/ledger/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const no_bukti = $(this).attr('no_bukti');
            $("#modalEdit").modal("show");
            $("#modalEdit").find(".modal-title").text('Edit Ledger');
            $("#modalEdit").find("#loadmodalEdit").load(`/ledger/${no_bukti}/edit`);
        });

    });
</script>
@endpush
