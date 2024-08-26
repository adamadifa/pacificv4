@extends('layouts.app')
@section('titlepage', 'Kas Kecil')

@section('content')
@section('navigasi')
    <span>Kas Kecil</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kaskecil')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('kaskecil.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Kas Kecil
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('kaskecil.index') }}">
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
                                    <div class="col">
                                        <x-input-with-icon label="No. Bukti" value="{{ Request('no_bukti_search') }}" name="no_bukti_search"
                                            icon="ti ti-barcode" />
                                    </div>
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
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
                                            <th style="width: 3%">No</th>
                                            <th style="width: 10%">Tanggal</th>
                                            <th style="width: 5%">No. Bukti</th>
                                            <th style="width: 20%">Keterangan</th>
                                            <th style="width: 20%">Akun</th>
                                            <th>Penerimaan</th>
                                            <th>Pengeluaran</th>
                                            <th>Saldo</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kaskecil as $d)
                                            @php
                                                $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                                $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->no_bukti }}</td>
                                                <td>{{ textCamelcase($d->keterangan) }}</td>
                                                <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                <td class="text-end">{{ formatAngka($penerimaan) }}</td>
                                                <td class="text-end">{{ formatAngka($pengeluaran) }}</td>
                                                <td></td>
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
