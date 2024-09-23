@extends('layouts.app')
@section('titlepage', 'Visit Pelanggan')

@section('content')
@section('navigasi')
    <span>Visit Pelanggan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    @can('pelanggan.show')
                        <form action="/visitpelanggan/cetak" method="GET" id="formCetak" target="_blank">
                            <input type="hidden" name="status_search" id='status_search' value="{{ Request('status_search') }}" />
                            <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                            <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman_search') }}" />
                            <input type="hidden" name="no_faktur" id="no_faktur_cetak" value="{{ Request('no_faktur_search') }}" />
                            <input type="hidden" name="kode_pelanggan" id="kode_pelanggan_cetak" value="{{ Request('kode_pelanggan_search') }}" />
                            <input type="hidden" name="nama_pelanggan" id="nama_pelanggan_cetak" value="{{ Request('nama_pelanggan_search') }}" />
                            <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                            <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('visitpelanggan.index') }}">
                            <input type="hidden" name="status_search" id='status_search' value="1" />
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
                                        <th>Salesman</th>
                                        <th>Cabang</th>
                                        <th>Tgl Faktur</th>
                                        <th>Nilai Faktur</th>
                                        <th>Tunai/Kredit</th>
                                        <th style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($visit as $d)
                                        <tr>
                                            <td>{{ $d->no_faktur }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ formatIndo($d->tanggal_faktur) }}</td>
                                            <td class="text-end">{{ formatRupiah($d->total_netto) }}</td>
                                            <td>{{ $d->jenis_transaksi == 'K' ? 'Kredit' : 'Tunai' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('penjualan.edit')
                                                        <a class="me-1 btnEdit" href="#" kode_visit = "{{ Crypt::encrypt($d->kode_visit) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('penjualan.show')
                                                        <div>
                                                            <a class="me-1 btnShow" href="#"
                                                                kode_visit = "{{ Crypt::encrypt($d->kode_visit) }}"><i
                                                                    class="ti ti-file-description text-info"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('penjualan.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="/visitpelanggan/{{ Crypt::encrypt($d->kode_visit) }}/delete">
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
                        <div style="float: right;">
                            {{ $visit->links() }}
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

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_visit = $(this).attr('kode_visit');
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Visit Pelangan");
            $("#loadmodal").load('/visitpelanggan/' + kode_visit + '/edit');
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var kode_visit = $(this).attr('kode_visit');
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Visit Pelangan");
            $("#loadmodal").load('/visitpelanggan/' + kode_visit + '/show');
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

        $("#formCetak").submit(function(e) {
            const status_search = $(this).find('#status_search').val();
            if (status_search == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silahkan Lakukan Pencarian Data Terlebih Dahulu',
                });
                return false;
            }
        });
    });
</script>
@endpush
