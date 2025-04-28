@extends('layouts.app')
@section('titlepage', 'Mutasi Keuangan')

@section('content')
@section('navigasi')
    <span>Mutasi Keuangan</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasikeuangan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('mutasikeuangan.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Mutasi Keuangan
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('mutasikeuangan.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                @if ($level_user != 'staff keuangan 2')
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
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
                                    </div>
                                @endif

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
                                            <th rowspan="2" style="width: 10%">Tanggal</th>
                                            <th colspan="2" style="width: 10%">No. Bukti</th>
                                            <th rowspan="2" style="width: 15%">Keterangan</th>
                                            <th rowspan="2" style="width: 10%">Kategori</th>
                                            <th rowspan="2" style="width: 5%">Debet</th>
                                            <th rowspan="2" style="width: 5%">Kredit</th>
                                            <th rowspan="2" style="width: 10%">Saldo</th>
                                            <th rowspan="2" style="width: 5%">#</th>
                                        </tr>
                                        <tr>
                                            <th>No. BTK</th>
                                            <th>No. BKK</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7">SALDO AWAL</th>
                                            <td
                                                class="text-end {{ $saldo_awal == null ? 'bg-danger text-white' : '' }}">
                                                @if ($saldo_awal != null)
                                                    {{ formatAngka($saldo_awal->jumlah - $mutasi->debet + $mutasi->kredit) }}
                                                @else
                                                    BELUM DI SET
                                                @endif
                                            </td>
                                            <td></td>
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
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td>{{ !empty($no_btk) ? 'BTK' . $no_btk : '' }}</td>
                                                <td>{{ !empty($no_bkk) ? 'BKK' . $no_bkk : '' }}</td>
                                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                                <td>{{ $d->nama_kategori }}</td>
                                                <td class="text-end">
                                                    {{ $d->debet_kredit == 'D' ? formatAngka($d->jumlah) : '' }} </td>
                                                <td class="text-end">
                                                    {{ $d->debet_kredit == 'K' ? formatAngka($d->jumlah) : '' }} </td>
                                                <td class="text-end">{{ formatAngka($saldo) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('mutasikeuangan.edit')
                                                            <a href="#" class="btnEdit me-1"
                                                                id="{{ Crypt::encrypt($d->id) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('mutasikeuangan.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('mutasikeuangan.delete', Crypt::encrypt($d->id)) }}">
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
                                            <td colspan="5">TOTAL</td>
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
