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
                                            <th style="width: 8%">Tanggal</th>
                                            <th style="width: 5%">Penerimaan</th>
                                            <th style="width: 15%">Pelanggan</th>
                                            <th style="width: 20%">Keterangan</th>
                                            <th style="width: 20%">Kode Akun</th>
                                            <th style="width: 5%">PRT</th>
                                            <th style="width: 5%">Debet</th>
                                            <th style="width: 5%">Kredit</th>
                                            <th style="width: 10%">Saldo</th>
                                            <th style="width: 5%">#</th>
                                        </tr>
                                        <tr>
                                            <th colspan="8">SALDO AWAL</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledger as $d)
                                            <tr>
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td></td>
                                                <td>{{ textCamelCase($d->pelanggan) }}</td>
                                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                                <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                <td>{{ $d->kode_peruntukan == 'MP' ? $d->kode_peruntukan : $d->keterangan_peruntukan }}</td>
                                                <td class="text-end">{{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '' }} </td>
                                                <td class="text-end">{{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '' }} </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
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

    });
</script>
@endpush
