@extends('layouts.app')
@section('titlepage', 'Mutasi Keuangan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Mutasi Keuangan</h4>
            <small class="text-muted">Riwayat dan manajemen transaksi mutasi keuangan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-arrows-transfer-down me-1"></i>Mutasi Keuangan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasikeuangan')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('mutasikeuangan.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        @if ($level_user != 'staff keuangan 2')
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <div class="form-group mb-3">
                                    <select name="kode_bank_search" id="kode_bank_search"
                                        class="form-select select2Kodebanksearch">
                                        <option value="">Pilih Bank</option>
                                        @foreach ($bank as $d)
                                            <option
                                                {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}
                                                value="{{ $d->kode_bank }}">{{ $d->nama_bank }}
                                                ({{ $d->no_rekening }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-7 col-md-6 col-sm-12"></div>
                        @endif
                        <div class="col-lg-1 col-md-12 col-sm-12 text-end">
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-transfer-down me-2"></i>Data Mutasi Keuangan</h6>
                    @can('mutasikeuangan.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Mutasi
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 8%">TANGGAL</th>
                            <th colspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 15%">NO. BUKTI</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 25%">KETERANGAN</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 10%">KATEGORI</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 10%">DEBET</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 10%">KREDIT</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 12%">SALDO</th>
                            <th rowspan="2" class="text-white text-center" style="background-color: #002e65 !important; width: 5%">#</th>
                        </tr>
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. BTK</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. BKK</th>
                        </tr>
                        <tr>
                            <th colspan="7" class="bg-light fw-bold text-dark">SALDO AWAL</th>
                            <td class="text-end fw-bold {{ $saldo_awal == null ? 'bg-danger text-white font-weight-bold' : 'bg-light text-dark' }}">
                                @if ($saldo_awal != null)
                                    {{ formatAngka($saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit) }}
                                @else
                                    BELUM DI SET
                                @endif
                            </td>
                            <td class="bg-light"></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $saldo =
                                $saldo_awal != null
                                    ? $saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit
                                    : 0;
                            $total_debet = 0;
                            $total_kredit = 0;
                        @endphp
                        @foreach ($mutasikeuangan as $d)
                            @php
                                $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                $no_btk = $d->debet_kredit == 'K' ? $d->no_bukti : '';
                                $no_bkk = $d->debet_kredit == 'D' ? $d->no_bukti : '';
                                $saldo = $saldo - $debet + $kredit;

                                $total_debet += $debet;
                                $total_kredit += $kredit;
                            @endphp
                            <tr>
                                <td class="text-center">{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                <td class="text-center">{{ !empty($no_btk) ? 'BTK' . $no_btk : '' }}</td>
                                <td class="text-center">{{ !empty($no_bkk) ? 'BKK' . $no_bkk : '' }}</td>
                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                <td class="text-center">{{ $d->nama_kategori }}</td>
                                <td class="text-end fw-bold text-danger">
                                    {{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '' }} </td>
                                <td class="text-end fw-bold text-success">
                                    {{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '' }} </td>
                                <td class="text-end fw-bold">{{ formatAngka($saldo) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('mutasikeuangan.edit')
                                            <a href="#" class="btnEdit text-success"
                                                id="{{ Crypt::encrypt($d->id) }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('mutasikeuangan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('mutasikeuangan.delete', Crypt::encrypt($d->id)) }}">
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
                            <th colspan="5" class="text-center" style="background-color: #002e65 !important;">TOTAL</th>
                            <th class="text-end" style="background-color: #002e65 !important;">{{ formatAngka($total_debet) }}</th>
                            <th class="text-end" style="background-color: #002e65 !important;">{{ formatAngka($total_kredit) }}</th>
                            <th class="text-end" style="background-color: #002e65 !important;">{{ formatAngka($saldo) }}</th>
                            <th style="background-color: #002e65 !important;"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
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
                    placeholder: 'Pilih Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Mutasi Keuangan');
            $("#loadmodal").load('/mutasikeuangan/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const id = $(this).attr('id');
            $("#modalEdit").modal("show");
            $("#modalEdit").find(".modal-title").text('Edit Mutasi Keuangan');
            $("#modalEdit").find("#loadmodalEdit").load(`/mutasikeuangan/${id}/edit`);
        });
    });
</script>
@endpush
