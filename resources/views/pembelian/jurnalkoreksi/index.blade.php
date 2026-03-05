@extends('layouts.app')
@section('titlepage', 'Jurnal Koreksi')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jurnal Koreksi</h4>
            <small class="text-muted">Kelola data jurnal koreksi pembelian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pembelian.index') }}"><i class="ti ti-shopping-cart-plus me-1"></i>Pembelian</a>
                </li>
                <li class="breadcrumb-item active">Jurnal Koreksi</li>
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
        min-width: 120px;
    }

    .freeze-3 {
        position: sticky;
        left: 240px;
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
        <form action="{{ route('jurnalkoreksi.index') }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="No. Bukti Pembelian" value="{{ Request('no_bukti_search') }}" name="no_bukti_search" icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-adjustments-horizontal me-2"></i>Data Jurnal Koreksi</h6>
                    <div class="d-flex gap-2">
                        @can('jurnalkoreksi.create')
                            <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Jurnal Koreksi</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white freeze-1">Tanggal</th>
                            <th class="text-white freeze-2">No. Bukti</th>
                            <th class="text-white freeze-3">Nama Barang</th>
                            <th class="text-white">Keterangan</th>
                            <th class="text-white">Akun</th>
                            <th class="text-white text-center">Qty</th>
                            <th class="text-white text-end">Harga</th>
                            <th class="text-white text-end">Debet</th>
                            <th class="text-white text-end">Kredit</th>
                            <th class="text-white text-center freeze-last" style="width: 5%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($jurnalkoreksi as $d)
                            @php
                                $total = $d->jumlah * $d->harga;
                                $debet = $d->debet_kredit == 'D' ? $total : 0;
                                $kredit = $d->debet_kredit == 'K' ? $total : 0;
                            @endphp
                            <tr>
                                <td class="freeze-1">{{ formatIndo($d->tanggal) }}</td>
                                <td class="freeze-2"><span class="fw-bold">{{ $d->no_bukti }}</span></td>
                                <td class="freeze-3">{{ $d->nama_barang }}</td>
                                <td>{{ $d->keterangan }}</td>
                                <td><span class="badge bg-label-secondary">{{ $d->kode_akun }}</span> {{ $d->nama_akun }}</td>
                                <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                <td class="text-end fw-bold">{{ formatAngkaDesimal($d->harga) }}</td>
                                <td class="text-end text-success fw-bold">{{ formatAngkaDesimal($debet) }}</td>
                                <td class="text-end text-danger fw-bold">{{ formatAngkaDesimal($kredit) }}</td>
                                <td class="freeze-last">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('jurnalkoreksi.create')
                                            <a href="#" class="btnEdit text-success" kode_jurnalkoreksi="{{ Crypt::encrypt($d->kode_jurnalkoreksi) }}"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('jurnalkoreksi.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('jurnalkoreksi.delete', Crypt::encrypt($d->kode_jurnalkoreksi)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
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
                    {{ $jurnalkoreksi->links() }}
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

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            var kode_jurnalkoreksi = $(this).attr("kode_jurnalkoreksi");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Jurnal Koreksi");
            $("#loadmodal").load(`/jurnalkoreksi/${kode_jurnalkoreksi}/edit`);
        });
    });
</script>
@endpush
