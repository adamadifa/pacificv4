@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        /* Table styles for all screen sizes */
        #rekapkategori-container {
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
            position: relative;
        }

        #rekapkategori {
            width: 100%;
            min-width: 600px;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Fixed columns styling */
        #rekapkategori th:nth-child(1),
        #rekapkategori td:nth-child(1) {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
        }

        #rekapkategori thead th:nth-child(1) {
            background-color: #212529;
        }

        #rekapkategori th:nth-child(2),
        #rekapkategori td:nth-child(2) {
            position: sticky;
            left: 120px;
            /* Width of first column */
            z-index: 2;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            box-shadow: 5px 0 8px -3px rgba(0, 0, 0, 0.15);
        }

        #rekapkategori thead th:nth-child(2) {
            background-color: #212529;
        }

        /* Header styling */
        #rekapkategori thead th {
            position: sticky;
            top: 0;
            background-color: #212529;
            color: white;
            z-index: 3;
        }

        /* Corner headers (fixed horizontally and vertically) */
        #rekapkategori thead th:nth-child(1),
        #rekapkategori thead th:nth-child(2) {
            z-index: 4;
        }

        /* Footer styling */
        #rekapkategori tfoot tr td {
            position: sticky;
            bottom: 0;
            background-color: #212529 !important;
            color: #fff !important;
            z-index: 2;
        }

        /* Column widths */
        #rekapkategori th:nth-child(1),
        #rekapkategori td:nth-child(1) {
            min-width: 120px;
            width: 120px;
        }

        /* #rekapkategori th:nth-child(2),
                                                                                                                                                                                                #rekapkategori td:nth-child(2) {
                                                                                                                                                                                                    min-width: 150px;
                                                                                                                                                                                                    width: 150px;
                                                                                                                                                                                                } */

        #rekapkategori th:nth-child(3),
        #rekapkategori td:nth-child(3),
        #rekapkategori th:nth-child(4),
        #rekapkategori td:nth-child(4) {
            min-width: 150px;
        }

        /* Cell styling */
        #rekapkategori th,
        #rekapkategori td {
            padding: 8px;
            white-space: nowrap;
        }

        .detaildebet {
            cursor: pointer;
        }

        .detailkredit {
            cursor: pointer;
        }

        .detailkategori {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 mb-2">
                        @php
                            $kode_bank = 'all';
                            $dari = !empty(Request('dari')) ? Request('dari') : date('Y-m-d');
                            $sampai = !empty(Request('sampai')) ? Request('sampai') : date('Y-m-d');
                        @endphp
                        <a href="{{ route('mutasikeuangan.show', [$kode_bank, $dari, $sampai]) }}">
                            <div class="card h-100 border-1 ">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="card-title mb-0">
                                        <h5 class="mb-0 me-2">{{ formatRupiah($rekap->total_saldo) }}</h5>
                                        <small>SALDO ALL REKENING</small>
                                        <br>
                                        <span class="fw-semibold"></span>
                                        {{-- <div class="d-flex align-items-center" style="font-size: 14px">
                                            <div class="d-flex flex-row align-items-center me-2">
                                                <i class="ti ti-arrow-down class text-success me-1"></i>
                                                <span class="text-success">{{ formatRupiah($rekap->total_rekap_kredit) }}</span>
                                            </div>
                                            <div class="d-flex flex-row align-items-center">
                                                <i class="ti ti-arrow-up class text-danger me-1"></i>
                                                <span class="text-danger">{{ formatRupiah($rekap->total_rekap_debet) }}</span>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="card-icon">
                                        <span class="badge bg-label-primary rounded-pill p-2">
                                            <i class="ti ti-moneybag ti-sm"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Rekening</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bank as $d)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('mutasikeuangan.show', [Crypt::encrypt($d->kode_bank), $dari, $sampai]) }}">
                                                            {{ $d->nama_bank_alias ? $d->nama_bank_alias : $d->nama_bank }}
                                                            {{ $d->no_rekening }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">{{ formatRupiah($d->saldo) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <form action="{{ URL::current() }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                                icon="ti ti-calendar" datepicker="flatpickr-date" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                                icon="ti ti-calendar" datepicker="flatpickr-date" />
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-3">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary w-100" id="showButton"><i
                                                    class="ti ti-heart-rate-monitor me-1"></i>Tampilkan</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" name="export" value="1"
                                                class="btn btn-success w-100" id="exportButton"><i
                                                    class="ti ti-file-export me-1"></i>Download</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rekapkategori-container"
                            style="display: block; width: 100%; overflow-x: auto;">
                            <table class="table table-bordered" id="rekapkategori">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        {{-- <th style="width: 15%">Bank</th>
                                        <th style="width: 15%">No Rekening</th> --}}
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                </thead>
                                <tbody>
                                    @php
                                        $totaldebet = 0;
                                        $totalkredit = 0;
                                    @endphp
                                    @foreach ($mutasi_kategori_detail as $d)
                                        @php
                                            $totaldebet += $d->debet;
                                            $totalkredit += $d->kredit;
                                        @endphp
                                        <tr>
                                            <td>{{ $d->tanggal }}</td>
                                            <td class="detailkategori" kode_kategori="{{ $d->kode_kategori }}"
                                                tanggal="{{ $d->tanggal }}" nama_kategori="{{ $d->nama_kategori }}">
                                                {{ $d->nama_kategori }}</td>
                                            {{-- <td>{{ $d->nama_bank }}</td>
                                            <td>{{ $d->no_rekening }}</td> --}}
                                            <td class="text-end detaildebet" kode_kategori="{{ $d->kode_kategori }}"
                                                tanggal="{{ $d->tanggal }}" nama_kategori="{{ $d->nama_kategori }}">
                                                {{ formatAngkaDesimal($d->debet) }}</td>
                                            <td class="text-end detailkredit" kode_kategori="{{ $d->kode_kategori }}"
                                                tanggal="{{ $d->tanggal }}" nama_kategori="{{ $d->nama_kategori }}">
                                                {{ formatAngkaDesimal($d->kredit) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Saldo Kas Besar Cabang</th>
                                        <td class="text-end">
                                            {{ $saldo_kasbesar_cabang ? formatRupiah($saldo_kasbesar_cabang->debet) : 0 }}
                                        </td>
                                        <td class="text-end">
                                            {{ $saldo_kasbesar_cabang ? formatRupiah($saldo_kasbesar_cabang->kredit) : 0 }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">TOTAL</td>
                                        <td class="text-end">
                                            @php
                                                $saldo_kb_debet = $saldo_kasbesar_cabang
                                                    ? $saldo_kasbesar_cabang->debet
                                                    : 0;
                                                $net_debet = $totaldebet + $saldo_kb_debet;
                                            @endphp
                                            {{ formatAngkaDesimal($net_debet) }}
                                        </td>
                                        <td class="text-end">
                                            @php
                                                $saldo_kb_kredit = $saldo_kasbesar_cabang
                                                    ? $saldo_kasbesar_cabang->kredit
                                                    : 0;
                                                $net_kredit = $totalkredit + $saldo_kb_kredit;
                                            @endphp
                                            {{ formatAngkaDesimal($net_kredit) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="table-dark" style="color:white !important">Net Profit</th>
                                    <td class="text-end">
                                        @php
                                            $net_profit = $net_kredit - $net_debet;
                                        @endphp
                                        {{ formatRupiah($net_profit) }}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- <div class="row">
                    @foreach ($kategori as $d)
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <a href=#">
                                <div class="card h-100 border-1 ">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-0 me-2">{{ $d->nama_kategori }}</h5>
                                            <div class="d-flex align-items-center" style="font-size: 14px">
                                                <div class="d-flex flex-row align-items-center me-2">
                                                    <i class="ti ti-arrow-down class text-success me-1"></i>
                                                    <span class="text-success">{{ formatRupiah($d->kredit) }}</span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center">
                                                    <i class="ti ti-arrow-up class text-danger me-1"></i>
                                                    <span class="text-danger">{{ formatRupiah($d->debet) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-icon">
                                            <span class="badge bg-label-primary rounded-pill p-2">
                                                <i class="ti ti-moneybag ti-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div> --}}
            </div>
        </div>
    </div>
    <x-modal-form id="modal" size="modal-fullscreen" show="detailmutasi" title="" />
@endsection
@push('myscript')
    <script>
        $(document).ready(function() {
            $(".detaildebet, .detailkredit, .detailkategori").on("click", function() {
                var kode_kategori = $(this).attr("kode_kategori");
                var tanggal = $(this).attr("tanggal");
                var nama_kategori = $(this).attr("nama_kategori");
                $.ajax({
                    url: "{{ route('mutasikeuangan.showmutasikategori') }}",
                    type: "GET",
                    data: {
                        kode_kategori: kode_kategori,
                        tanggal: tanggal
                    },
                    success: function(response) {
                        $("#modal").modal("show");
                        $("#modal").find(".modal-title").html("Detail Transaksi " +
                            nama_kategori +
                            " " + tanggal);
                        $("#modal").find("#detailmutasi").html(response);
                    }
                });
            });
        });
    </script>
@endpush
