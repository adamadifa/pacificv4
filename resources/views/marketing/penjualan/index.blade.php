@extends('layouts.app')
@section('titlepage', 'Penjualan')

@section('content')
@section('navigasi')
    <span>Penjualan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">

        <div class="alert alert-info alert-dismissible d-flex align-items-baseline" role="alert">
            <span class="alert-icon alert-icon-lg text-info me-2">
                <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Informasi</h5>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-file-invoice text-danger me-1 ms-1"></i> Untuk Membatalkan
                    Faktur !
                </p>
                <p class="mb-0">
                    Silahkan Gunakan Icon <i class="ti ti-adjustments text-warning me-1 ms-1"></i> Untuk Generate No.
                    Faktur
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                @can('penjualan.create')
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Penjualan</a>
                @endcan
                @can('penjualan.cetakfaktur')
                    <a href="#" class="btn btn-success" id="btnCetakSuratjalan"><i class="ti ti-printer me-2"></i>
                        Cetak Banyak Surat Jalan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('penjualan.index') }}">
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
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_salesman_search" id="kode_salesman_search" class="form-select select2Kodesalesmansearch">
                                            <option value="">Salesman</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search"
                                        icon="ti ti-barcode" />
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}"
                                        name="kode_pelanggan_search" icon="ti ti-barcode" />
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}"
                                        name="nama_pelanggan_search" icon="ti ti-users" />
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
                            <table class="table table-bordered ">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 10%">No. Faktur</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 15%">Nama Pelanggan</th>
                                        <th>Nama Cabang</th>
                                        <th>Salesman</th>
                                        <th>Total</th>
                                        <th>JT</th>
                                        <th>Status</th>
                                        <th style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                                $color = '#0084d14f';
                                                $color_text = '#000';
                                            } else {
                                                $color = '';
                                                $color_text = '';
                                            }
                                        @endphp

                                        <tr style="background-color: {{ $color }}; color:{{ $color_text }}">
                                            <td>{{ $d->no_faktur }} </td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td>{{ strtoupper($d->nama_cabang) }}</td>
                                            <td>{{ strtoupper($d->nama_salesman) }}</td>
                                            <td class="text-end">{{ formatAngka($total_netto) }}</td>
                                            <td>
                                                {{-- {{ $d->jenis_transaksi }} --}}
                                                @if ($d->jenis_transaksi == 'T')
                                                    <span class="badge bg-success">{{ $d->jenis_transaksi }}</span>
                                                @elseif($d->jenis_transaksi == 'K')
                                                    <span class="badge bg-warning">{{ $d->jenis_transaksi }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status_batal == 0)
                                                    @if ($d->total_bayar == $total_netto)
                                                        <span class="badge bg-success">Lunas</span>
                                                    @elseif ($d->total_bayar > $total_netto)
                                                        <span class="badge bg-info">Lunas</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Lunas</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Batal</span>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('penjualan.edit')
                                                        <a class="me-1" href="/penjualan/{{ \Crypt::encrypt($d->no_faktur) }}/edit"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('penjualan.show')
                                                        <div>
                                                            <a class="me-1" href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}"><i
                                                                    class="ti ti-file-description text-info"></i></a>
                                                        </div>
                                                    @endcan

                                                    @can('penjualan.show')
                                                        <div me-1>
                                                            <div class="btn-group">
                                                                <a href="#" class="dropdown-toggle waves-effect waves-light"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-printer text-primary"></i>
                                                                </a>
                                                                <ul class="dropdown-menu" style="">
                                                                    @can('penjualan.cetakfaktur')
                                                                        <li>
                                                                            <a class="dropdown-item" target="_blank"
                                                                                href="{{ route('penjualan.cetakfaktur', Crypt::encrypt($d->no_faktur)) }}">
                                                                                <i class="ti ti-printer me-1"></i>
                                                                                CetakFaktur
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('penjualan.cetaksuratjalan')
                                                                        <li>
                                                                            <a class="dropdown-item" target="_blank"
                                                                                href="{{ route('penjualan.cetaksuratjalan', [1, Crypt::encrypt($d->no_faktur)]) }}">
                                                                                <i class="ti ti-printer me-1"></i>Cetak Surat
                                                                                Jalan 1
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" target="_blank"
                                                                                href="{{ route('penjualan.cetaksuratjalan', [2, Crypt::encrypt($d->no_faktur)]) }}">
                                                                                <i class="ti ti-printer me-1"></i>Cetak Surat
                                                                                Jalan 2
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                    @can('penjualan.batalfaktur')
                                                        <div>
                                                            <a href="#" class="ms-4 btnBatal" no_faktur="{{ Crypt::encrypt($d->no_faktur) }}"><i
                                                                    class="ti ti-file-invoice text-danger"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('penjualan.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/delete">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                    @can('penjualan.edit')
                                                        @if (substr($d->no_faktur, 3, 2) == 'PR')
                                                            <a href="/penjualan/{{ Crypt::encrypt($d->no_faktur) }}/generatefaktur">
                                                                <i class="ti ti-adjustments text-warning me-1"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('visitpelanggan.create')
                                                        {{-- {{ $d->kode_visit }} --}}
                                                        @if (!empty($d->kode_visit))
                                                            <i class="ti ti-checks text-success"></i>
                                                        @else
                                                            <a href="#" no_faktur = "{{ Crypt::encrypt($d->no_faktur) }}" class="btnVisit">
                                                                <i class="ti ti-gps text-primary me-1"></i>
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
                            {{ $penjualan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
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
