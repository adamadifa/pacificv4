@extends('layouts.app')
@section('titlepage', 'Surat Jalan Angkutan')

@section('content')
@section('navigasi')
    <span>Surat Jalan Angkutan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('suratjalanangkutan.index') }}">
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
                                    <x-input-with-icon label="No. Dokumen" name="no_dok_search" icon="ti ti-barcode"
                                        value="{{ Request('no_dok_search') }}" />
                                </div>
                            </div>
                            <x-select label="Angkutan" name="kode_angkutan_search" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan"
                                select2="select2Kodeangkutansearch" upperCase="true" selected="{{ Request('kode_angkutan_search') }}" />
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
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Dok</th>
                                        <th>Tanggal</th>
                                        <th>Tujuan</th>
                                        <th>Angkutan</th>
                                        <th>No. Polisi</th>
                                        <th>Tarif</th>
                                        <th>Tepung</th>
                                        <th>BS</th>
                                        <th>Kontrabon</th>
                                        <th>Tanggal Bayar</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suratjalanangkutan as $d)
                                        <tr>
                                            <td>{{ $d->no_dok }}</td>
                                            <td>{{ DateToIndo($d->tanggal) }}</td>
                                            <td>{{ $d->tujuan }}</td>
                                            <td>{{ $d->nama_angkutan }}</td>
                                            <td>{{ $d->no_polisi }}</td>
                                            <td class="text-end">{{ formatAngka($d->tarif) }}</td>
                                            <td class="text-end">{{ formatAngka($d->tepung) }}</td>
                                            <td class="text-end">{{ formatAngka($d->bs) }}</td>
                                            <td>
                                                @if ($d->tanggal_kontrabon != null)
                                                    <span class="badge bg-success">{{ formatIndo($d->tanggal_kontrabon) }}</span>
                                                @else
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->tanggal_ledger || !empty($d->tanggal_ledger_hutang)))
                                                    <span class="badge bg-success">
                                                        {{ formatIndo($d->tanggal_ledger ?? $d->tanggal_ledger_hutang) }}
                                                    </span>
                                                @else
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('suratjalanangkutan.edit')
                                                        @if (empty($d->tanggal_kontrabon))
                                                            <a href="#" class="btnEdit" no_dok = "{{ Crypt::encrypt($d->no_dok) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $suratjalanangkutan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@push('myscript')
<script>
    $(function() {
        const select2Kodeangkutansearch = $('.select2Kodeangkutansearch');
        if (select2Kodeangkutansearch.length) {
            select2Kodeangkutansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Angkutan',
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const no_dok = $(this).attr('no_dok');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Angkutan");
            $("#loadmodal").load(`/suratjalanangkutan/${no_dok}/edit`);
        });
    });
</script>
@endpush
