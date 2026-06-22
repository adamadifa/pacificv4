@extends('layouts.app')
@section('titlepage', 'Visit Pelanggan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Visit Pelanggan</h4>
            <small class="text-muted">Manajemen visit pelanggan dan kunjungan sales.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Worksheet OM</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Visit Pelanggan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('visitpelanggan.index') }}" id="formSearch">
            <input type="hidden" name="status_search" id='status_search' value="1" />
            <div class="row g-2 mb-1">
                <div class="col-lg-4 col-sm-12 col-md-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-4 col-sm-12 col-md-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-4 col-sm-12 col-md-12">
                    @php
                        $berdasarkan_data = [
                            (object) ['kode' => 'tanggal_kunjungan', 'nama' => 'Berdasarkan Tanggal Kunjungan'],
                            (object) ['kode' => 'tanggal_faktur', 'nama' => 'Berdasarkan Tanggal Faktur']
                        ];
                    @endphp
                    <x-select label="Berdasarkan" name="berdasarkan_tanggal" :data="collect($berdasarkan_data)" key="kode"
                        textShow="nama" selected="{{ Request('berdasarkan_tanggal') ?? 'tanggal_kunjungan' }}" hideLabel="true" />
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

            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-3 col-sm-12 col-md-12">
                    <x-select label="Salesman" name="kode_salesman_search" :data="[]" key="kode_salesman"
                        textShow="nama_salesman" select2="select2Kodesalesmansearch" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search"
                        icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan_search') }}"
                        name="kode_pelanggan_search" icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}"
                        name="nama_pelanggan_search" icon="ti ti-users" hideLabel="true" />
                </div>
                <div class="col-auto">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Data Visit Pelanggan</h6>
                    <div class="d-flex gap-2">
                        @can('pelanggan.show')
                            <form action="/visitpelanggan/cetak" method="GET" id="formCetak" target="_blank" class="d-flex gap-2">
                                <input type="hidden" name="status_search" id='status_search' value="{{ Request('status_search') }}" />
                                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                                <input type="hidden" name="berdasarkan_tanggal" id="berdasarkan_tanggal_cetak" value="{{ Request('berdasarkan_tanggal') }}" />
                                <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                                <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman_search') }}" />
                                <input type="hidden" name="no_faktur" id="no_faktur_cetak" value="{{ Request('no_faktur_search') }}" />
                                <input type="hidden" name="kode_pelanggan" id="kode_pelanggan_cetak" value="{{ Request('kode_pelanggan_search') }}" />
                                <input type="hidden" name="nama_pelanggan" id="nama_pelanggan_cetak" value="{{ Request('nama_pelanggan_search') }}" />
                                <button class="btn btn-primary btn-sm"><i class="ti ti-printer me-1"></i>Cetak</button>
                                <button class="btn btn-success btn-sm" name="exportButton"><i class="ti ti-download me-1"></i>Export</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white py-3" style="width: 10%">No. Faktur</th>
                            <th class="text-white py-3" style="width: 10%">Tanggal</th>
                            <th class="text-white py-3" style="width: 15%">Nama Pelanggan</th>
                            <th class="text-white py-3">Salesman</th>
                            <th class="text-white py-3">Cabang</th>
                            <th class="text-white py-3">Tgl Faktur</th>
                            <th class="text-white py-3">Nilai Faktur</th>
                            <th class="text-white py-3">Tunai/Kredit</th>
                            <th class="text-white py-3">JK</th>
                            <th class="text-white text-center py-3" style="width: 10%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($visit as $d)
                            <tr>
                                <td class="py-2">{{ $d->no_faktur }}</td>
                                <td class="py-2">{{ formatIndo($d->tanggal) }}</td>
                                <td class="py-2">{{ $d->nama_pelanggan }}</td>
                                <td class="py-2">{{ $d->nama_salesman }}</td>
                                <td class="py-2">{{ $d->kode_cabang }}</td>
                                <td class="py-2">{{ formatIndo($d->tanggal_faktur) }}</td>
                                <td class="py-2 text-end fw-bold">{{ formatRupiah($d->total_netto) }}</td>
                                <td class="py-2">{{ $d->jenis_transaksi == 'K' ? 'Kredit' : 'Tunai' }}</td>
                                <td class="py-2">{{ $d->jenis_kunjungan }}</td>
                                <td class="py-2">
                                    <div class="d-flex justify-content-center">
                                        @can('penjualan.edit')
                                            <a class="me-2 btnEdit text-success" href="#" kode_visit = "{{ Crypt::encrypt($d->kode_visit) }}"><i
                                                    class="ti ti-edit fs-5"></i></a>
                                        @endcan
                                        @can('penjualan.show')
                                            <a class="me-2 btnShow text-info" href="#"
                                                kode_visit = "{{ Crypt::encrypt($d->kode_visit) }}"><i
                                                    class="ti ti-file-description fs-5"></i></a>
                                        @endcan
                                        @can('penjualan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="/visitpelanggan/{{ Crypt::encrypt($d->kode_visit) }}/delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
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
                    {{ $visit->links() }}
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
