@extends('layouts.app')
@section('titlepage', 'Jatuh Tempo')

@section('content')
@section('navigasi')
    <span>Jatuh Tempo</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('pembelian.jatuhtempo') }}" id="formSearch">
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="No. Bukti Pembelian" value="{{ Request('no_bukti_search') }}" name="no_bukti_search"
                                        icon="ti ti-barcode" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('jatuhtempo_dari') }}" name="jatuhtempo_dari"
                                        icon="ti ti-calendar" datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('jatuhtempo_sampai') }}" name="jatuhtempo_sampai"
                                        icon="ti ti-calendar" datepicker="flatpickr-date" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_asal_pengajuan_search" id="kode_asal_pengajuan_search" class="form-select">
                                            <option value="">Asal Ajuan</option>
                                            @foreach ($asal_ajuan as $d)
                                                <option value="{{ $d['kode_group'] }}"
                                                    {{ Request('kode_asal_pengajuan_search') == $d['kode_group'] ? 'selected' : '' }}>
                                                    {{ $d['nama_group'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier"
                                        textShow="nama_supplier" upperCase="true" selected="{{ Request('kode_supplier_search') }}"
                                        select2="select2Kodesupplier" />
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <select name="ppn_search" id="ppn_search" class="form-select">
                                        <option value="">PPN / Non PPN</option>
                                        <option value="1" {{ Request('ppn_search') == '1' ? 'selected' : '' }}>PPN</option>
                                        <option value="0" {{ Request('ppn_search') === '0' ? 'selected' : '' }}>Non PPN</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <select name="jenis_transaksi_search" id="jenis_transaksi_search" class="form-select">
                                        <option value="">Tunai / Kredit</option>
                                        <option value="T" {{ Request('jenis_transaksi_search') == 'T' ? 'selected' : '' }}>Tunai</option>
                                        <option value="K" {{ Request('jenis_transaksi_search') == 'K' ? 'selected' : '' }}>Kredit</option>
                                    </select>
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
                                        <th style="width: 10%">No. Bukti</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width:20%">Supplier</th>
                                        <th style="width: 5%">Ajuan</th>
                                        <th style="width: 10%">Jatuh Tempo</th>
                                        <th>Total</th>
                                        <th>Bayar</th>
                                        <th>PPN</th>
                                        <th>KB</th>
                                        <th>Ket</th>
                                        <th>T/K</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $d)
                                        @php
                                            $total = $d->subtotal + $d->penyesuaian_jk;

                                        @endphp
                                        <tr>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nama_supplier }}</td>
                                            <td>{{ $d->kode_asal_pengajuan }}</td>
                                            <td>{{ formatIndo($d->jatuh_tempo) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($d->totalbayar) }}</td>
                                            <td class="text-center">
                                                @if ($d->ppn == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->cek_kontrabon > 0)
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($total == $d->totalbayar)
                                                    <span class="badge bg-success">L</span>
                                                @else
                                                    <span class="badge bg-danger">BL</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $d->jenis_transaksi == 'T' ? 'bg-success' : 'bg-warning' }}">{{ $d->jenis_transaksi }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    {{-- @can('pembelian.edit')
                                                        <a href="{{ route('pembelian.edit', Crypt::encrypt($d->no_bukti)) }}" class="btnEdit">
                                                            <i class="ti ti-edit text-success me-1"></i>
                                                        </a>
                                                    @endcan --}}
                                                    @can('pembelian.show')
                                                        <a href="#" class="btnShow" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                            <i class="ti ti-file-description text-info me-1"></i>
                                                        </a>
                                                    @endcan
                                                    {{-- @can('pembelian.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('pembelian.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $pembelian->links() }}
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
        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Supplier',
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

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Detail Pembelian");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/show`);
            $("#modal").find(".modal-dialog").addClass('modal-xl');
        });

    });
</script>
@endpush
