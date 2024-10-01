@extends('layouts.app')
@section('titlepage', 'Jurnal Umum')

@section('content')
@section('navigasi')
    <span>Jurnal Umum</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jurnalumum.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Jurnal Umum</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('jurnalumum.index') }}" id="formSearch">
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
                                    <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        upperCase="true" selected="{{ Request('kode_cabang_search') }}" select2="select2Kodecabangsearch" />
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
                                        <th style="width: 10%">Kode JU</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 25%">Keterangan</th>
                                        <th style="width: 20%">Akun</th>
                                        <th style="width: 8%">Peruntukan</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>Dept</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jurnalumum as $d)
                                        @php
                                            $debet = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                            $kredit = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                            $color_cr = !empty($d->kode_cr) ? 'bg-primary text-white' : '';
                                        @endphp
                                        <tr class="{{ $color_cr }}">
                                            <td>{{ $d->kode_ju }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                            <td>{{ $d->kode_peruntukan }} {{ !empty($d->kode_cabang) ? '(' . $d->kode_cabang . ')' : '' }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($debet) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($kredit) }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jurnalumum.edit')
                                                        <a href="#" class="btnEdit me-1" kode_ju="{{ Crypt::encrypt($d->kode_ju) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('jurnalumum.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('jurnalumum.delete', Crypt::encrypt($d->kode_ju)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
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
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

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
            $("#modal").find(".modal-title").text("Input Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/create`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });


        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_ju = $(this).attr('kode_ju');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Jurnal Umum");
            $("#modal").find("#loadmodal").load(`/jurnalumum/${kode_ju}/edit`);
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });

    });
</script>
@endpush
