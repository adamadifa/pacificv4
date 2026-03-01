@extends('layouts.app')
@section('titlepage', 'Jatuh Tempo')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jatuh Tempo</h4>
            <small class="text-muted">Monitoring data jatuh tempo pembelian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pembelian.index') }}"><i class="ti ti-shopping-cart-plus me-1"></i>Pembelian</a>
                </li>
                <li class="breadcrumb-item active">Jatuh Tempo</li>
            </ol>
        </nav>
    </div>
@endsection
<style>
    .freeze-1 {
        position: sticky;
        left: 0;
        z-index: 2;
        min-width: 120px;
    }

    .freeze-2 {
        position: sticky;
        left: 120px;
        z-index: 2;
        min-width: 100px;
    }

    .freeze-3 {
        position: sticky;
        left: 220px;
        z-index: 2;
        min-width: 200px;
    }

    .freeze-last {
        position: sticky;
        right: 0;
        z-index: 2;
        border-left: 1px solid #dee2e6;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
    }

    /* background color for body cells to avoid transparency */
    tbody td.freeze-1,
    tbody td.freeze-2,
    tbody td.freeze-3,
    tbody td.freeze-last {
        background-color: #fff !important;
    }

    /* background and z-index for headers */
    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-last {
        background-color: #002e65 !important;
        z-index: 3;
    }

    thead th {
        background-color: #002e65 !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('pembelian.jatuhtempo') }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="No. Bukti Pembelian" value="{{ Request('no_bukti_search') }}" name="no_bukti_search" icon="ti ti-barcode" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Dari" value="{{ Request('jatuhtempo_dari') }}" name="jatuhtempo_dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Sampai" value="{{ Request('jatuhtempo_sampai') }}" name="jatuhtempo_sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
            </div>

            <div class="row g-2 mb-1">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="kode_asal_pengajuan_search" id="kode_asal_pengajuan_search" class="form-select">
                            <option value="">Asal Ajuan</option>
                            @foreach ($asal_ajuan as $d)
                                <option value="{{ $d['kode_group'] }}" {{ Request('kode_asal_pengajuan_search') == $d['kode_group'] ? 'selected' : '' }}>
                                    {{ $d['nama_group'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier" textShow="nama_supplier" upperCase="true"
                        selected="{{ Request('kode_supplier_search') }}" select2="select2Kodesupplier" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <select name="ppn_search" id="ppn_search" class="form-select">
                        <option value="">PPN / Non PPN</option>
                        <option value="1" {{ Request('ppn_search') == '1' ? 'selected' : '' }}>PPN</option>
                        <option value="0" {{ Request('ppn_search') === '0' ? 'selected' : '' }}>Non PPN</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <select name="jenis_transaksi_search" id="jenis_transaksi_search" class="form-select">
                        <option value="">Tunai / Kredit</option>
                        <option value="T" {{ Request('jenis_transaksi_search') == 'T' ? 'selected' : '' }}>Tunai</option>
                        <option value="K" {{ Request('jenis_transaksi_search') == 'K' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group mb-1">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar-time me-2"></i>Data Jatuh Tempo</h6>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white freeze-1" style="width: 10%">No. Bukti</th>
                            <th class="text-white freeze-2" style="width: 10%">Tanggal</th>
                            <th class="text-white freeze-3" style="width: 20%">Supplier</th>
                            <th class="text-white text-center">Ajuan</th>
                            <th class="text-white text-center">Jatuh Tempo</th>
                            <th class="text-white text-end">Total</th>
                            <th class="text-white text-end">Bayar</th>
                            <th class="text-white text-center">PPN</th>
                            <th class="text-white text-center">KB</th>
                            <th class="text-white text-center">Ket</th>
                            <th class="text-white text-center">T/K</th>
                            <th class="text-white text-center freeze-last" style="width: 5%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($pembelian as $d)
                            @php
                                $total = $d->subtotal + $d->penyesuaian_jk;
                            @endphp
                            <tr>
                                <td class="freeze-1"><span class="fw-bold">{{ $d->no_bukti }}</span></td>
                                <td class="freeze-2">{{ formatIndo($d->tanggal) }}</td>
                                <td class="freeze-3">{{ $d->nama_supplier }}</td>
                                <td class="text-center"><span class="badge bg-label-info">{{ $d->kode_asal_pengajuan }}</span></td>
                                <td class="text-center fw-bold text-danger">{{ formatIndo($d->jatuh_tempo) }}</td>
                                <td class="text-end fw-bold">{{ formatAngkaDesimal($total) }}</td>
                                <td class="text-end text-success fw-bold">{{ formatAngkaDesimal($d->totalbayar) }}</td>
                                <td class="text-center">
                                    @if ($d->ppn == '1')
                                        <i class="ti ti-checks text-success"></i>
                                    @else
                                        <i class="ti ti-minus text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->cek_kontrabon > 0)
                                        <span class="badge bg-label-success"><i class="ti ti-checks me-1"></i> KB</span>
                                    @else
                                        <span class="badge bg-label-warning"><i class="ti ti-hourglass-empty me-1"></i> KB</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($total == $d->totalbayar)
                                        <span class="badge bg-success shadow-sm">Lunas</span>
                                    @else
                                        <span class="badge bg-danger shadow-sm">Belum Lunas</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $d->jenis_transaksi == 'T' ? 'bg-label-success' : 'bg-label-warning' }}">
                                        {{ $d->jenis_transaksi == 'T' ? 'Tunai' : 'Kredit' }}
                                    </span>
                                </td>
                                <td class="freeze-last">
                                    <div class="d-flex justify-content-center">
                                        @can('pembelian.show')
                                            <a href="#" class="btnShow text-info" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $pembelian->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" size="modal-xl" />
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
        });
    });
</script>
@endpush
