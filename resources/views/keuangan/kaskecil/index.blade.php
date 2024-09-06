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
                                            <th style="width: 10%">No. Bukti</th>
                                            <th style="width: 20%">Keterangan</th>
                                            <th style="width: 20%">Akun</th>
                                            <th>Penerimaan</th>
                                            <th>Pengeluaran</th>
                                            <th>Saldo</th>
                                            <th>Aksi</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7"><b>SALDO AWAL</b></th>
                                            <td class="text-end">{{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</td>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                                            $total_penerimaan = 0;
                                            $total_pengeluaran = 0;
                                        @endphp
                                        @foreach ($kaskecil as $d)
                                            @php
                                                $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                                $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                                $color = $d->debet_kredit == 'K' ? 'success' : 'danger';
                                                $saldo += $penerimaan - $pengeluaran;
                                                $total_penerimaan += $penerimaan;
                                                $total_pengeluaran += $pengeluaran;
                                                $colorklaim = !empty($d->kode_klaim) ? 'bg-success text-white' : '';
                                                $colorcr = !empty($d->kode_cr) ? 'bg-primary text-white' : '';
                                            @endphp
                                            <tr>
                                                <td class="{{ $colorklaim }}">{{ $loop->iteration }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td class="{{ $colorcr }}">{{ $d->no_bukti }}</td>
                                                <td>{{ textCamelcase($d->keterangan) }}</td>
                                                <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                                <td class="text-end text-{{ $color }}">{{ formatAngka($penerimaan) }}</td>
                                                <td class="text-end text-{{ $color }}">{{ formatAngka($pengeluaran) }}</td>
                                                <td class="text-end text-primary"> {{ formatAngka($saldo) }}</td>
                                                <td>
                                                    @if ($d->keterangan != 'Penerimaan Kas Kecil')
                                                        <div class="d-flex">
                                                            @can('kaskecil.edit')
                                                                <a href="#" class="btnEdit me-1" id="{{ Crypt::encrypt($d->id) }}"><i
                                                                        class="ti ti-edit text-success"></i>
                                                                </a>
                                                            @endcan
                                                            @can('kaskecil.delete')
                                                                @if (empty($d->kode_klaim))
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('kaskecil.delete', Crypt::encrypt($d->id)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i class="ti ti-trash text-danger"></i>

                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endcan

                                                        </div>
                                                    @endif


                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot class="table-dark">
                                        <tr>
                                            <th colspan="5">TOTAL</th>
                                            <td class="text-end">{{ formatAngka($total_penerimaan) }}</td>
                                            <td class="text-end">{{ formatAngka($total_pengeluaran) }}</td>
                                            <td class="text-end">{{ formatAngka($saldo) }}</td>
                                            <th></th>
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

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Kas Kecil');
            $("#loadmodal").load('/kaskecil/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const id = $(this).attr('id');
            $("#modalEdit").modal("show");
            $("#modalEdit").find(".modal-title").text('Edit Kaskecil');
            $("#modalEdit").find("#loadmodalEdit").load(`/kaskecil/${id}/edit`);
        });

    });
</script>
@endpush
