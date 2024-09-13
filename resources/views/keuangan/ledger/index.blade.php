@extends('layouts.app')
@section('titlepage', 'Ledger')

@section('content')
@section('navigasi')
    <span>Ledger</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ledger')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('ledger.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Ledger
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('ledger.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
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
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 10%">Tanggal</th>
                                            <th style="width: 5%">Penerimaan</th>
                                            <th style="width: 10%">Pelanggan</th>
                                            <th style="width: 15%">Keterangan</th>
                                            <th style="width: 20%">Kode Akun</th>
                                            <th style="width: 5%">PRT</th>
                                            <th style="width: 5%">Debet</th>
                                            <th style="width: 5%">Kredit</th>
                                            <th style="width: 10%">Saldo</th>
                                            <th style="width: 5%">#</th>
                                        </tr>
                                        <tr>
                                            <th colspan="8">SALDO AWAL</th>
                                            <td class="text-end {{ $saldo_awal == null ? 'bg-danger text-white' : '' }}">
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
                                                $color_cr = !empty($d->kode_cr) ? 'bg-primary text-white' : '';
                                                $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                                $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                                $saldo = $saldo - $debet + $kredit;

                                                $total_debet += $debet;
                                                $total_kredit += $kredit;
                                            @endphp
                                            <tr class="{{ $color_cr }}">
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td></td>
                                                <td>{{ textCamelCase($d->pelanggan) }}</td>
                                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                                <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                <td>{{ $d->kode_peruntukan == 'MP' ? $d->kode_peruntukan : $d->keterangan_peruntukan }}</td>
                                                <td class="text-end">{{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '' }} </td>
                                                <td class="text-end">{{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '' }} </td>
                                                <td class="text-end">{{ formatAngka($saldo) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('ledger.edit')
                                                            <a href="#" class="btnEdit me-1" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('ledger.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ledger.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>

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
                                            <td colspan="6">TOTAL</td>
                                            <td class="text-end">{{ formatAngka($total_debet) }}</td>
                                            <td class="text-end">{{ formatAngka($total_kredit) }}</td>
                                            <td class="text-end">{{ formatAngka($saldo) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
