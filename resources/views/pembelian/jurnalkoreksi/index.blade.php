@extends('layouts.app')
@section('titlepage', 'Jurnal Koreksi')

@section('content')
@section('navigasi')
    <span>Jurnal Koreksi</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input
                        Jurnal Koreksi</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('jurnalkoreksi.index') }}" id="formSearch">
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="No. Bukti Pembelian" value="{{ Request('no_bukti_search') }}" name="no_bukti_search"
                                        icon="ti ti-barcode" />
                                </div>
                            </div>

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
                                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
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
                            <table class="table  table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Bukti</th>
                                        <th>Nama Barang</th>
                                        <th>Keterangan</th>
                                        <th>Akun</th>
                                        <th class="text-center">Qty</th>
                                        <th>Harga</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jurnalkoreksi as $d)
                                        @php
                                            $total = $d->jumlah * $d->harga;
                                            $debet = $d->debet_kredit == 'D' ? $total : 0;
                                            $kredit = $d->debet_kredit == 'K' ? $total : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ $d->nama_barang }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                            <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($debet) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($kredit) }}</td>
                                            <td>
                                                @can('jurnalkoreksi.delete')
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('jurnalkoreksi.delete', Crypt::encrypt($d->kode_jurnalkoreksi)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm me-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $jurnalkoreksi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
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
            $(".modal-title").text("Input Jurnal Koreksi");
            $("#loadmodal").load(`/jurnalkoreksi/create`);
        });
    });
</script>
@endpush
