@extends('layouts.app')
@section('titlepage', 'Penjualan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Penjualan</h4>
            <small class="text-muted">Kelola data transaksi penjualan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-shopping-cart me-1"></i>Penjualan</li>
            </ol>
        </nav>
    </div>
@endsection
<style>
    .freeze-1 {
        position: sticky;
        left: 0;
        z-index: 2;
    }

    .freeze-2 {
        position: sticky;
        left: 100px; /* Lebar kolom No. Faktur approx */
        z-index: 2;
    }

    .freeze-3 {
        position: sticky;
        left: 200px; /* Lebar kolom No. Faktur + Tanggal approx */
        z-index: 2;
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

    /* background and z-index for headers - NO VERTICAL STICKY */
    thead th {
        background-color: #002e65 !important;
    }

    /* Make sure sticky headers have higher z-index than sticky body cells */
    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-last {
        z-index: 3;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="alert alert-info alert-dismissible d-flex align-items-baseline shadow-sm" role="alert">
            <span class="alert-icon alert-icon-lg text-info me-2">
                <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Informasi</h5>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-file-invoice text-danger me-1 ms-1"></i> Untuk Membatalkan Faktur !
                </p>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-adjustments text-warning me-1 ms-1"></i> Untuk Generate No. Faktur
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('penjualan.index') }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
            </div>
            @hasanyrole($roles_show_cabang)
                <div class="row g-2 mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                            select2="select2Kodecabangsearch" />
                    </div>
                </div>
            @endrole

            <div class="row g-2 mb-1">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="kode_salesman_search" id="kode_salesman_search" class="form-select select2Kodesalesmansearch">
                            <option value="">Salesman</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search" icon="ti ti-barcode" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}" name="kode_pelanggan_search" icon="ti ti-barcode" />
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" name="nama_pelanggan_search" icon="ti ti-users" />
                </div>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-auto">
                    <div class="form-group mb-1 text-end">
                        <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-shopping-cart me-2"></i>Data Penjualan</h6>
                    <div class="d-flex gap-2">
                        @can('penjualan.cetakfaktur')
                            <a href="#" class="btn btn-success btn-sm" id="btnCetakSuratjalan"><i class="ti ti-printer me-1"></i> Cetak Banyak Surat Jalan</a>
                        @endcan
                        @can('penjualan.create')
                            <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Penjualan</a>
                        @endcan
                    </div>
                </div>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white freeze-1" style="width: 10%">No. Faktur</th>
                            <th class="text-white freeze-2" style="width: 10%">Tanggal</th>
                            <th class="text-white freeze-3" style="width: 15%">Nama Pelanggan</th>
                            <th class="text-white">Nama Cabang</th>
                            <th class="text-white">Salesman</th>
                            <th class="text-white">Total</th>
                            <th class="text-white">JT</th>
                            <th class="text-white">Status</th>
                            <th class="text-white text-center freeze-last" style="width: 10%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($penjualan as $d)
                            @php
                                $total_netto =
                                    $d->total_bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                                if ($d->status_batal == '1') {
                                    $color = '#ed9993';
                                    $color_text = '#000';
                                } elseif ($d->status_batal == '2') {
                                    $color = '#edd993';
                                    $color_text = '#000';
                                } elseif (substr($d->no_faktur, 3, 2) == 'PR') {
                                    $color = '#b0d9f1';
                                    $color_text = '#000';
                                } else {
                                    $color = '';
                                    $color_text = '';
                                }
                            @endphp

                            <tr style="background-color: {{ $color != '' ? $color : '#fff' }} !important; color:{{ $color_text }}">
                                <td class="freeze-1" style="background-color: {{ $color != '' ? $color : '#fff' }} !important;"><span class="fw-bold">{{ $d->no_faktur }}</span></td>
                                <td class="freeze-2" style="background-color: {{ $color != '' ? $color : '#fff' }} !important;">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="freeze-3" style="background-color: {{ $color != '' ? $color : '#fff' }} !important;">{{ $d->nama_pelanggan }}</td>
                                <td>{{ strtoupper($d->nama_cabang) }}</td>
                                <td>{{ strtoupper($d->nama_salesman) }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($total_netto) }}</td>
                                <td>
                                    @if ($d->jenis_transaksi == 'T')
                                        <span class="badge bg-success shadow-sm">{{ $d->jenis_transaksi }}</span>
                                    @elseif($d->jenis_transaksi == 'K')
                                        <span class="badge bg-warning shadow-sm">{{ $d->jenis_transaksi }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->status_batal == 0)
                                        @if ($d->total_bayar == $total_netto)
                                            <span class="badge bg-success shadow-sm">Lunas</span>
                                        @elseif ($d->total_bayar > $total_netto)
                                            <span class="badge bg-info shadow-sm">Lunas</span>
                                        @else
                                            <span class="badge bg-danger shadow-sm">Belum Lunas</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger shadow-sm">Batal</span>
                                    @endif
                                </td>
                                <td class="freeze-last" style="background-color: {{ $color != '' ? $color : '#fff' }} !important;">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('penjualan.edit')
                                            <a href="/penjualan/{{ \Crypt::encrypt($d->no_faktur) }}/edit" class="text-success" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('penjualan.show')
                                            <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" class="text-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                            <div class="btn-group">
                                                <a href="#" class="dropdown-toggle waves-effect waves-light hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-printer text-primary fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @can('penjualan.cetakfaktur')
                                                        <li>
                                                            <a class="dropdown-item" target="_blank" href="{{ route('penjualan.cetakfaktur', Crypt::encrypt($d->no_faktur)) }}">
                                                                <i class="ti ti-printer me-2"></i> Cetak Faktur
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('penjualan.cetaksuratjalan')
                                                        <li>
                                                            <a class="dropdown-item" target="_blank" href="{{ route('penjualan.cetaksuratjalan', [1, Crypt::encrypt($d->no_faktur)]) }}">
                                                                <i class="ti ti-printer me-2"></i> Cetak Surat Jalan 1
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" target="_blank" href="{{ route('penjualan.cetaksuratjalan', [2, Crypt::encrypt($d->no_faktur)]) }}">
                                                                <i class="ti ti-printer me-2"></i> Cetak Surat Jalan 2
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        @endcan
                                        @can('penjualan.batalfaktur')
                                            <a href="#" class="btnBatal text-danger" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}" data-bs-toggle="tooltip" title="Batalkan Faktur">
                                                <i class="ti ti-file-invoice fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('penjualan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline" action="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('penjualan.edit')
                                            @if (substr($d->no_faktur, 3, 2) == 'PR')
                                                <a href="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/generatefaktur" class="text-warning" data-bs-toggle="tooltip" title="Generate Faktur">
                                                    <i class="ti ti-adjustments fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('visitpelanggan.create')
                                            @if (!empty($d->kode_visit))
                                                <i class="ti ti-checks text-success fs-5" data-bs-toggle="tooltip" title="Sudah Visit"></i>
                                            @else
                                                <a href="#" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}" class="btnVisit text-primary" data-bs-toggle="tooltip" title="Visit Pelanggan">
                                                    <i class="ti ti-gps fs-5"></i>
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $penjualan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<style>
    /* Fix sticky column overlap when dropdown is open */
    .tr-dropdown-active {
        z-index: 9999 !important;
        position: relative;
    }
    .td-dropdown-active {
        z-index: 9999 !important;
    }
    .table-responsive-dropdown-active {
        padding-bottom: 120px;
    }
</style>
@endsection
@push('myscript')
<script>
    $(function() {
        // Fix for dropdown getting hidden under next row's frozen column
        $('.table-responsive').on('show.bs.dropdown', function (e) {
            $(this).addClass('table-responsive-dropdown-active');
            $(e.target).closest('tr').addClass('tr-dropdown-active');
            $(e.target).closest('td.freeze-last').addClass('td-dropdown-active');
        });
        $('.table-responsive').on('hide.bs.dropdown', function (e) {
            $(this).removeClass('table-responsive-dropdown-active');
            $(e.target).closest('tr').removeClass('tr-dropdown-active');
            $(e.target).closest('td.freeze-last').removeClass('td-dropdown-active');
        });
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCetakSuratjalan").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Cetak Surat Jalan");
            $("#loadmodal").load(`/penjualan/filtersuratjalan`);
        });

        $(".btnVisit").click(function(e) {
            e.preventDefault();
            no_faktur = $(this).attr('no_faktur');
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Visit Pelanggan");
            $("#loadmodal").load(`/visitpelanggan/${no_faktur}/create`);
        });

        $(".btnBatal").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = $(this).attr('no_faktur');
            $("#modal").modal("show");
            $(".modal-title").text("Ubah Ke Faktur Batal");
            $("#loadmodal").load(`/penjualan/${no_faktur}/batalfaktur`);
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });
    });
</script>
@endpush
