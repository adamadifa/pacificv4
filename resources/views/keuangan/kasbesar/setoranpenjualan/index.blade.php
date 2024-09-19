@extends('layouts.app')
@section('titlepage', 'Setoran Penjualan')

@section('content')
@section('navigasi')
    <span>Setoran Penjualan</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kasbesar')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="d-flex justify-content-between">
                        @can('setoranpenjualan.create')
                            <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                                Input Setoran Penjualan
                            </a>
                        @endcan
                        @can('setoranpenjualan.show')
                            <form action="/setoranpenjualan/cetak" method="GET" id="formCetak" target="_blank">
                                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                                <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                                <input type="hidden" name="kode_salesman_search" id="kode_salesman_cetak"
                                    value="{{ Request('kode_salesman_search') }}" />
                                <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                                <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                            </form>
                        @endcan
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('setoranpenjualan.index') }}">
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
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_salesman_search" id="kode_salesman_search"
                                                class="form-select select2Kodesalesmansearch">
                                                <option value="">Semua Salesman</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
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
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" class="align-middle">Tanggal</th>
                                            <th rowspan="2" class="align-middle">Salesman</th>
                                            <th colspan="2" class="text-center bg-success">Penjualan</th>
                                            <th rowspan="2" class="align-middle bg-success">Total LHP</th>
                                            <th colspan="5" class="text-center bg-danger">Setoran</th>
                                            <th rowspan="2" class="align-middle bg-danger">Total Setoran</th>
                                            <th rowspan="2" class="align-middle"></th>
                                        </tr>
                                        <tr>
                                            <th class="bg-success">Tunai</th>
                                            <th class="bg-success">Tagihan</th>

                                            <th class="bg-danger">Kertas</th>
                                            <th class="bg-danger">Logam</th>
                                            <th class="bg-danger">Giro</th>
                                            <th class="bg-danger">Transfer</th>
                                            <th class="bg-danger">Lainnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal_lhp_tunai = 0;
                                            $subtotal_lhp_tagihan = 0;
                                            $subtotal_total_lhp = 0;

                                            $subtotal_setoran_kertas = 0;
                                            $subtotal_setoran_logam = 0;
                                            $subtotal_setoran_lainnya = 0;
                                            $subtotal_setoran_transfer = 0;
                                            $subtotal_setoran_giro = 0;
                                            $subtotal_total_setoran = 0;

                                        @endphp
                                        @foreach ($setoran_penjualan as $key => $d)
                                            @php
                                                $next_tanggal = @$setoran_penjualan[$key + 1]->tanggal;
                                                $total_lhp = $d->lhp_tunai + $d->lhp_tagihan;
                                                $uk = $d->kurangsetorkertas - $d->lebihsetorkertas;
                                                $ul = $d->kurangsetorlogam - $d->lebihsetorlogam;
                                                $setoran_kertas = $d->setoran_kertas + $uk;
                                                $setoran_logam = $d->setoran_logam + $ul;
                                                $total_setoran =
                                                    $setoran_kertas + $setoran_logam + $d->setoran_giro + $d->setoran_transfer + $d->setoran_lainnya;

                                                $subtotal_lhp_tunai += $d->lhp_tunai;
                                                $subtotal_lhp_tagihan += $d->lhp_tagihan;
                                                $subtotal_total_lhp += $total_lhp;

                                                $subtotal_setoran_kertas += $setoran_kertas;
                                                $subtotal_setoran_logam += $setoran_logam;
                                                $subtotal_setoran_lainnya += $d->setoran_lainnya;
                                                $subtotal_setoran_transfer += $d->setoran_transfer;
                                                $subtotal_setoran_giro += $d->setoran_giro;
                                                $subtotal_total_setoran += $total_setoran;

                                                $cek_tagihan = $d->cek_lhp_tagihan + $d->cek_lhp_giro + $d->cek_lhp_transfer;
                                                $color_setoran_tunai = $d->lhp_tunai == $d->cek_lhp_tunai ? 'bg-success' : 'bg-danger';
                                                $color_setoran_tagihan = $d->lhp_tagihan == $cek_tagihan ? 'bg-success' : 'bg-danger';
                                                $cek_giro_to_cash_transfer = $d->cek_giro_to_cash_transfer;
                                                $giro_to_cash_transfer = $d->giro_to_cash + $d->giro_to_transfer;

                                                if (
                                                    $d->lhp_tunai == $d->cek_lhp_tunai &&
                                                    $d->lhp_tagihan == $cek_tagihan &&
                                                    $giro_to_cash_transfer == $cek_giro_to_cash_transfer
                                                ) {
                                                    $color_total_lhp = 'bg-success';
                                                } else {
                                                    $color_total_lhp = 'bg-danger';
                                                }

                                                if ($uk > 0) {
                                                    $opkertas = '+';
                                                } else {
                                                    $opkertas = '+';
                                                }

                                                if ($ul > 0) {
                                                    $oplogam = '+';
                                                } else {
                                                    $oplogam = '+';
                                                }

                                                $selisih = $total_setoran - $total_lhp;

                                                $kontenkertas = formatRupiah($d->setoran_kertas) . $opkertas . formatRupiah($uk);
                                                $kontenlogam = formatRupiah($d->setoran_logam) . $opkertas . formatRupiah($ul);

                                                if ($loop->iteration % 2) {
                                                    $position = 'right';
                                                } else {
                                                    $position = 'left';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td>
                                                    @php
                                                        $nama_salesman = explode(' ', $d->nama_salesman);
                                                        $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                                                    @endphp
                                                    {{ $nama_depan }}
                                                </td>
                                                <td class="text-end {{ $color_setoran_tunai }} text-white">
                                                    {{ formatAngka($d->lhp_tunai) }}</td>
                                                <td class="text-end {{ $color_setoran_tagihan }} text-white">
                                                    {{ formatAngka($d->lhp_tagihan) }}</td>
                                                <td class="text-end {{ $color_total_lhp }} text-white cursor-pointer showlhp"
                                                    tanggal="{{ $d->tanggal }}" kode_salesman="{{ $d->kode_salesman }}">
                                                    {{ formatAngka($total_lhp) }}
                                                </td>
                                                <td class="text-end cursor-pointer" data-bs-toggle="popover"
                                                    data-bs-placement="{{ $position }}" data-bs-html="true"
                                                    data-bs-content="{!! $kontenkertas !!}" title="Rincian Setoran Kertas"
                                                    data-bs-custom-class="popover-info">
                                                    {{ formatAngka($setoran_kertas) }}
                                                </td>
                                                <td class="text-end cursor-pointer" data-bs-toggle="popover"
                                                    data-bs-placement="{{ $position }}" data-bs-html="true"
                                                    data-bs-content="{!! $kontenlogam !!}" title="Rincian Setoran Logam"
                                                    data-bs-custom-class="popover-info">
                                                    {{ formatAngka($setoran_logam) }}
                                                </td>
                                                <td class="text-end">{{ formatAngka($d->setoran_giro) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_transfer) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_lainnya) }}</td>
                                                <td class="text-end">{{ formatAngka($total_setoran) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('setoranpenjualan.show')
                                                            <div>
                                                                <a href="#" class="me-1" data-bs-toggle="popover"
                                                                    data-bs-placement="{{ $position }}" data-bs-html="true"
                                                                    data-bs-content="{!! $d->keterangan !!}" title="Keterangan"
                                                                    data-bs-custom-class="popover-info">
                                                                    <i class="ti ti-info-square text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('setoranpenjualan.edit')
                                                            <div>
                                                                <a href="#" class="btnEdit me-1"
                                                                    kode_setoran = "{{ Crypt::encrypt($d->kode_setoran) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('setoranpenjualan.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('setoranpenjualan.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @if ($d->tanggal != $next_tanggal)
                                                <tr class="table-dark">
                                                    <td colspan='2'>TOTAL</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_lhp_tunai) }}</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_lhp_tagihan) }}</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_total_lhp) }}</td>

                                                    <td class="text-end">

                                                        {{ formatAngka($subtotal_setoran_kertas) }}

                                                    </td>
                                                    <td class="text-end">{{ formatAngka($subtotal_setoran_logam) }}</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_setoran_giro) }}</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_setoran_transfer) }}</td>
                                                    <td class="text-end">{{ formatAngka($subtotal_setoran_lainnya) }}
                                                    <td class="text-end">{{ formatAngka($subtotal_total_setoran) }}</td>
                                                    <td></td>
                                                </tr>
                                                @php
                                                    $subtotal_lhp_tunai = 0;
                                                    $subtotal_lhp_tagihan = 0;
                                                    $subtotal_total_lhp = 0;

                                                    $subtotal_setoran_kertas = 0;
                                                    $subtotal_setoran_logam = 0;
                                                    $subtotal_setoran_lainnya = 0;
                                                    $subtotal_setoran_transfer = 0;
                                                    $subtotal_setoran_giro = 0;
                                                    $subtotal_total_setoran = 0;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalDetaillhp" show="loadmodaldetaillhp" title="Detail LHP" size="modal-xl" />
@endsection
@push('myscript')
<script>
    $(function() {
        const formCetak = $("#formCetak");

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        function loadingShowlhp() {
            $("#loadmodaldetaillhp").html(`<div class="sk-wave sk-primary" style="margin:auto">
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
                    placeholder: 'Pilih  Cabang',
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
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Pembayaran Setoran');
            $("#loadmodal").load('/setoranpenjualan/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            kode_setoran = $(this).attr("kode_setoran");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Edit Pembayaran Setoran');
            $("#loadmodal").load(`/setoranpenjualan/${kode_setoran}/edit`);
        });

        $(".showlhp").click(function(e) {
            e.preventDefault();
            loadingShowlhp();
            $("#modalDetaillhp").modal("show");
            const tanggal = $(this).attr("tanggal");
            const kode_salesman = $(this).attr("kode_salesman");
            $.ajax({
                type: 'POST',
                url: '/setoranpenjualan/showlhp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#loadmodaldetaillhp").html(respond);
                }
            });
        });


        $("#formCetak").submit(function(e) {
            var dari = $("#dari_cetak").val();
            var sampai = $("#sampai_cetak").val();
            var kode_cabang = $("#kode_cabang_cetak").val();
            var kode_salesman = $("#kode_salesman_cetak").val();

            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Lakukan Pencarian Data Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
