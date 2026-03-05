@extends('layouts.app')
@section('titlepage', 'Setoran Penjualan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Setoran Penjualan</h4>
            <small class="text-muted">Manajemen setoran hasil penjualan per salesman.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report-money me-1"></i>Setoran Penjualan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Action & Filter Section --}}
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="card-body p-0">
                <form action="{{ route('setoranpenjualan.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-2 col-md-6">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endrole
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                name="nama_pelanggan_search" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group mb-3">
                                <select name="kode_salesman_search" id="kode_salesman_search"
                                    class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-6">
                            <div class="form-group mb-3">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-12">
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @can('setoranpenjualan.show')
            <form action="/setoranpenjualan/cetak" method="GET" id="formCetak" target="_blank" class="d-none">
                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                <input type="hidden" name="kode_salesman_search" id="kode_salesman_cetak"
                    value="{{ Request('kode_salesman_search') }}" />
            </form>
        @endcan

        {{-- Data Table Section --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Setoran Penjualan</h6>
                <div class="d-flex gap-2">
                    @can('setoranpenjualan.show')
                        <button form="formCetak" class="btn btn-sm btn-outline-white text-white border-white" type="submit">
                            <i class="ti ti-printer me-1"></i>Cetak
                        </button>
                        <button form="formCetak" class="btn btn-sm btn-outline-success text-success border-success bg-white" name="exportButton" type="submit">
                            <i class="ti ti-download me-1"></i>Export
                        </button>
                    @endcan
                    @can('setoranpenjualan.create')
                        <a href="#" class="btn btn-sm btn-primary shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Setoran
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-white align-middle" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th rowspan="2" class="text-white align-middle" style="background-color: #002e65 !important;">SALESMAN</th>
                            <th colspan="2" class="text-white text-center" style="background-color: #28a745 !important;">PENJUALAN</th>
                            <th rowspan="2" class="text-white align-middle text-center" style="background-color: #28a745 !important;">TOTAL LHP</th>
                            <th colspan="5" class="text-white text-center" style="background-color: #dc3545 !important;">SETORAN</th>
                            <th rowspan="2" class="text-white align-middle text-center" style="background-color: #dc3545 !important;">TOTAL SETORAN</th>
                            <th rowspan="2" class="text-white text-center align-middle" style="background-color: #002e65 !important;">#</th>
                        </tr>
                        <tr>
                            <th class="text-white text-center" style="background-color: #28a745c4 !important;">TUNAI</th>
                            <th class="text-white text-center" style="background-color: #28a745c4 !important;">TAGIHAN</th>

                            <th class="text-white text-center" style="background-color: #dc3545c4 !important;">KERTAS</th>
                            <th class="text-white text-center" style="background-color: #dc3545c4 !important;">LOGAM</th>
                            <th class="text-white text-center" style="background-color: #dc3545c4 !important;">GIRO</th>
                            <th class="text-white text-center" style="background-color: #dc3545c4 !important;">TRANSFER</th>
                            <th class="text-white text-center" style="background-color: #dc3545c4 !important;">LAINNYA</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
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
                                $color_setoran_tunai = $d->lhp_tunai == $d->cek_lhp_tunai ? 'text-success' : 'text-danger fw-bold';
                                $color_setoran_tagihan = $d->lhp_tagihan == $cek_tagihan ? 'text-success' : 'text-danger fw-bold';
                                $cek_giro_to_cash_transfer = $d->cek_giro_to_cash_transfer;
                                $giro_to_cash_transfer = $d->giro_to_cash + $d->giro_to_transfer;

                                if (
                                    $d->lhp_tunai == $d->cek_lhp_tunai &&
                                    $d->lhp_tagihan == $cek_tagihan &&
                                    $giro_to_cash_transfer == $cek_giro_to_cash_transfer
                                ) {
                                    $color_total_lhp = 'text-success';
                                } else {
                                    $color_total_lhp = 'text-danger fw-bold';
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
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>
                                    @php
                                        $nama_salesman = explode(' ', $d->nama_salesman);
                                        $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                                    @endphp
                                    <span class="fw-bold">{{ $nama_depan }}</span>
                                </td>
                                <td class="text-end {{ $color_setoran_tunai }}">
                                    {{ formatAngka($d->lhp_tunai) }}</td>
                                <td class="text-end {{ $color_setoran_tagihan }}">
                                    {{ formatAngka($d->lhp_tagihan) }}</td>
                                <td class="text-end {{ $color_total_lhp }} cursor-pointer showlhp"
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
                                <td class="text-end fw-bold">{{ formatAngka($total_setoran) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('setoranpenjualan.show')
                                            <a href="#" class="text-info" data-bs-toggle="popover"
                                                data-bs-placement="{{ $position }}" data-bs-html="true"
                                                data-bs-content="{!! $d->keterangan !!}" title="Keterangan"
                                                data-bs-custom-class="popover-info">
                                                <i class="ti ti-info-circle fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('setoranpenjualan.edit')
                                            <a href="#" class="btnEdit text-success"
                                                kode_setoran = "{{ Crypt::encrypt($d->kode_setoran) }}">
                                                <i class="ti ti-edit fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('setoranpenjualan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('setoranpenjualan.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </a>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @if ($d->tanggal != $next_tanggal)
                                <tr class="table-light fw-bold">
                                    <td colspan='2' class="text-center">TOTAL HARIAN</td>
                                    <td class="text-end text-primary">{{ formatAngka($subtotal_lhp_tunai) }}</td>
                                    <td class="text-end text-primary">{{ formatAngka($subtotal_lhp_tagihan) }}</td>
                                    <td class="text-end text-primary">{{ formatAngka($subtotal_total_lhp) }}</td>

                                    <td class="text-end text-danger">
                                        {{ formatAngka($subtotal_setoran_kertas) }}
                                    </td>
                                    <td class="text-end text-danger">{{ formatAngka($subtotal_setoran_logam) }}</td>
                                    <td class="text-end text-danger">{{ formatAngka($subtotal_setoran_giro) }}</td>
                                    <td class="text-end text-danger">{{ formatAngka($subtotal_setoran_transfer) }}</td>
                                    <td class="text-end text-danger">{{ formatAngka($subtotal_setoran_lainnya) }}
                                    <td class="text-end text-danger">{{ formatAngka($subtotal_total_setoran) }}</td>
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
