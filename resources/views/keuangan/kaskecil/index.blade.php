@extends('layouts.app')
@section('titlepage', 'Kas Kecil')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kas Kecil</h4>
            <small class="text-muted">Manajemen operasional kas kecil.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-wallet me-1"></i>Kas Kecil</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_kaskecil')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('kaskecil.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                value="{{ Request('no_bukti_search') }}" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                        <div class="col-lg-1 col-md-2 col-sm-12">
                            <div class="form-group mb-3 text-end">
                                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Data Operasional Kas Kecil</h6>
                    @can('kaskecil.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Kas Kecil
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 3%;">NO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 10%;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 10%;">NO. BUKTI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 25%;">KETERANGAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 20%;">AKUN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">PENERIMAAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">PENGELUARAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">SALDO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">AKSI</th>
                        </tr>
                        <tr style="background-color: #f8f9fa;">
                            <th colspan="7" class="fw-bold px-3">SALDO AWAL</th>
                            <td class="text-end fw-bold text-primary">
                                {{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</td>
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
                                <td class="text-center {{ $colorklaim }}">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                                <td class="text-center {{ $colorcr }} fw-bold">{{ $d->no_bukti }}</td>
                                <td style="white-space: normal;">{{ textCamelcase($d->keterangan) }}</td>
                                <td style="white-space: normal;">{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                                <td class="text-end text-{{ $color }} fw-bold">{{ formatAngka($penerimaan) }}</td>
                                <td class="text-end text-{{ $color }} fw-bold">{{ formatAngka($pengeluaran) }}</td>
                                <td class="text-end text-primary fw-bold"> {{ formatAngka($saldo) }}</td>
                                <td class="text-center">
                                    @if ($d->keterangan != 'Penerimaan Kas Kecil')
                                        <div class="d-flex justify-content-center gap-1">
                                            @can('kaskecil.edit')
                                                <a href="#" class="btnEdit text-success" id="{{ Crypt::encrypt($d->id) }}"
                                                    data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                            @endcan
                                            @can('kaskecil.delete')
                                                @if (empty($d->kode_klaim))
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('kaskecil.delete', Crypt::encrypt($d->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
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
                            <th colspan="5" class="text-center">TOTAL</th>
                            <td class="text-end fw-bold">{{ formatAngka($total_penerimaan) }}</td>
                            <td class="text-end fw-bold">{{ formatAngka($total_pengeluaran) }}</td>
                            <td class="text-end fw-bold text-white">{{ formatAngka($saldo) }}</td>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
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
