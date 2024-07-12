@extends('layouts.app')
@section('titlepage', 'Pembelian')

@section('content')
@section('navigasi')
    <span>Pembelian</span>
@endsection
<div class="row">

    <div class="@can('pembelian.harga') col-lg-12 @else col-lg-8 @endcan  col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('pembelian.create')
                        <a href="{{ route('pembelian.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Pembelian</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('pembelian.index') }}" id="formSearch">
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
                            @can('pembelian.harga')
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
                            @else
                                <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier"
                                    textShow="nama_supplier" upperCase="true" selected="{{ Request('kode_supplier_search') }}"
                                    select2="select2Kodesupplier" />
                            @endcan


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
                                        <th style="width:25%">Supplier</th>
                                        <th style="width: 5%">Ajuan</th>
                                        {{-- <th>Subtotal</th>
                                        <th>Peny</th> --}}
                                        @can('pembelian.harga')
                                            <th>Total</th>
                                            <th>Bayar</th>
                                            <th>PPN</th>
                                            <th>KB</th>
                                            <th>Ket</th>
                                            <th>T/K</th>
                                        @endcan



                                        <th style="width: 10%">#</th>
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
                                            {{-- <td class="text-end">{{ formatAngkaDesimal($d->subtotal) }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($d->penyesuaian_jk) }}</td> --}}
                                            @can('pembelian.harga')
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
                                            @endcan
                                            <td>
                                                <div class="d-flex">
                                                    @can('pembelian.edit')
                                                        <a href="{{ route('pembelian.edit', Crypt::encrypt($d->no_bukti)) }}" class="btnEdit">
                                                            <i class="ti ti-edit text-success me-1"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pembelian.show')
                                                        <a href="#" class="btnShow" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                            <i class="ti ti-file-description text-info me-1"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pembelian.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('pembelian.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                    @can('pembelian.approvegdl')
                                                        @if ($d->kode_asal_pengajuan == 'GDL')
                                                            @if (empty($d->no_bukti_gdl))
                                                                <a href="#" class="btnApprovegdl" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                    <i class="ti ti-external-link text-primary"></i>
                                                                </a>
                                                            @else
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('pembelian.cancelapprovegdl', Crypt::encrypt($d->no_bukti)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm me-1">
                                                                        <i class="ti ti-xbox-x text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endcan
                                                    @can('pembelian.approvemtc')
                                                        @if ($d->kode_asal_pengajuan == 'GAF' && $d->cekmaintenance > 0)
                                                            @if (empty($d->no_bukti_mtc))
                                                                <a href="#" class="btnApprovemtc" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                    <i class="ti ti-external-link text-warning"></i>
                                                                </a>
                                                            @else
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('pembelian.cancelapprovemtc', Crypt::encrypt($d->no_bukti)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm me-1">
                                                                        <i class="ti ti-xbox-x text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
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
                            {{ $pembelian->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@can('pembelian.harga')
    <x-modal-form id="modal" show="loadmodal" title="" size="modal-xl" />
@else
    <x-modal-form id="modal" show="loadmodal" title="" size="modal-lg" />
@endcan
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
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });


        $(".btnApprovegdl").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Approve Penerimaan Gudang Logistik");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/approvegdl`);
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });


        $(".btnApprovemtc").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Approve Penerimaan Maintenance");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/approvemtc`);
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });

    });
</script>
@endpush
