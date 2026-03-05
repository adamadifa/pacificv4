@extends('layouts.app')
@section('titlepage', 'Monitoring Retur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Monitoring Retur</h4>
            <small class="text-muted">Pantau data transaksi retur barang dan pelunasannya.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Worksheet OM</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-receipt-refund me-1"></i>Monitoring Retur</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ URL::current() }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
            </div>
            @hasanyrole($roles_show_cabang)
                <div class="row g-2 mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                            select2="select2Kodecabangsearch" hideLabel="true" />
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
                    <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search" icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}" name="kode_pelanggan_search" icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" name="nama_pelanggan_search" icon="ti ti-users" hideLabel="true" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt-refund me-2"></i>Data Monitoring Retur</h6>
                    <div class="d-flex gap-2">
                        @can('pelanggan.show')
                            <form action="/monitoringretur/cetak" method="GET" id="formCetak" target="_blank" class="d-flex gap-2">
                                <input type="hidden" name="status_search" id='status_search' value="{{ Request('status_search') }}" />
                                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                                <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                                <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman_search') }}" />
                                <input type="hidden" name="no_faktur" id="no_faktur_cetak" value="{{ Request('no_faktur_search') }}" />
                                <input type="hidden" name="kode_pelanggan" id="kode_pelanggan_cetak" value="{{ Request('kode_pelanggan_search') }}" />
                                <input type="hidden" name="nama_pelanggan" id="nama_pelanggan_cetak" value="{{ Request('nama_pelanggan_search') }}" />
                                <button class="btn btn-primary btn-sm"><i class="ti ti-printer me-1"></i>Cetak</button>
                                <button class="btn btn-success btn-sm" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white">No. Retur</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">No. Faktur</th>
                            <th class="text-white">Nama Pelanggan</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Salesman</th>
                            <th class="text-white">Jenis Retur</th>
                            <th class="text-white text-end">Total</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center" style="position: sticky; right: 0; background-color: #002e65; z-index: 10; width: 10%;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($retur as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->no_retur }}</span></td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->no_faktur }}</td>
                                <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                                <td>{{ textUpperCase($d->kode_cabang_baru) }}</td>
                                <td>{{ textUpperCase($d->nama_salesman) }}</td>
                                <td>
                                    @if ($d->jenis_retur == 'GB')
                                        <span class="badge bg-success shadow-sm">Ganti Barang</span>
                                    @else
                                        <span class="badge bg-danger shadow-sm">Potong Faktur</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">{{ formatRupiah($d->total_retur) }}</td>
                                <td class="text-center">
                                    @if ($d->jenis_retur == 'GB')
                                        @php
                                            $sisa_retur = $d->total_qty_retur - $d->total_qty_pelunasan;
                                        @endphp
                                        @if ($sisa_retur == 0)
                                            <span class="badge bg-success shadow-sm">L</span>
                                        @else
                                            <span class="badge bg-danger shadow-sm">BL</span>
                                        @endif
                                    @else
                                        <i class="ti ti-circle-minus text-warning"></i>
                                    @endif
                                </td>
                                <td style="position: sticky; right: 0; background-color: #fff; z-index: 9;">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('worksheetom.monitoringretur')
                                            <a href="#" class="btnPelunasan text-info" no_retur="{{ Crypt::encrypt($d->no_retur) }}" title="{{ $d->no_retur }}" data-bs-toggle="tooltip" title="Pelunasan">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                            <a href="#" class="btnCheck text-danger" no_retur="{{ Crypt::encrypt($d->no_retur) }}" data-bs-toggle="tooltip" title="Check">
                                                <i class="ti ti-list-check fs-5"></i>
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
                    {{ $retur->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
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

        $(".btnCheck").click(function(e) {
            e.preventDefault();
            const no_retur = $(this).attr('no_retur');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Checking Retur");
            $("#loadmodal").load(`/monitoringretur/${no_retur}/create`);
        });

        $(".btnPelunasan").click(function(e) {
            e.preventDefault();
            const no_retur = $(this).attr('no_retur');
            const title = $(this).attr('title');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Pelunasan Retur " + title);
            $("#loadmodal").load(`/pelunasanretur/${no_retur}/create`);
        });
    });
</script>
@endpush
