@extends('layouts.app')
@section('titlepage', 'Kontrol BBM Kendaraan')

@section('content')
@section('navigasi')
    <span>Kontrol BBM Kendaraan</span>
@endsection

<div class="row">
    <div class="col-12">

        <div class="nav-align-top nav-tabs-shadow mb-4">
            <div class="tab-content">
                <div class="tab-pane fade active show">
                    @php
                        $jabatan = auth()->user()->kode_jabatan ?? null;
                    @endphp

                    @if ($jabatan == 'J08')
                        <a href="#" class="btn btn-primary" id="btnCreate">
                            <i class="fa fa-plus me-2"></i>Tambah Data
                        </a>
                    @endif
                    <div class="row mt-3">
                        <div class="col-12">

                            <form method="GET" action="{{ route('worksheetom.bbm') }}" id="frmSearchBBM">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-2">
                                        <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari_search"
                                            id="dari_search" value="{{ request('dari_search') }}"
                                            datepicker="flatpickr-date" />
                                    </div>

                                    <div class="col-lg-2">
                                        <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai_search"
                                            id="sampai_search" value="{{ request('sampai_search') }}"
                                            datepicker="flatpickr-date" />
                                    </div>

                                    <div class="col-lg-2">
                                        <select name="kode_kendaraan_search" class="form-select">
                                            <option value="">Pilih Kendaraan</option>
                                            @foreach ($kendaraan as $k)
                                                <option value="{{ $k->kode_kendaraan }}"
                                                    {{ request('kode_kendaraan_search') == $k->kode_kendaraan ? 'selected' : '' }}>
                                                    {{ $k->no_polisi . ' - ' . $k->tipe_kendaraan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select name="kode_driver_helper_search" class="form-select select2Driver">
                                            <option value="">Pilih Driver</option>
                                            @foreach ($driver as $d)
                                                <option value="{{ $d->kode_driver_helper }}"
                                                    {{ request('kode_driver_helper_search') == $d->kode_driver_helper ? 'selected' : '' }}>
                                                    {{ $d->nama_driver_helper }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-2">
                                        <select name="kode_cabang_search" class="form-select select2Kodecabangsearch">
                                            <option value="">Pilih Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option value="{{ $d->kode_cabang }}"
                                                    {{ request('kode_cabang_search') == $d->kode_cabang ? 'selected' : '' }}>
                                                    {{ $d->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-2">
                                        <select name="jenis_laporan_search" class="form-select">
                                            <option value="detail"
                                                {{ request('jenis_laporan_search') == 'detail' ? 'selected' : '' }}>
                                                Detail
                                            </option>
                                            <option value="rekap"
                                                {{ request('jenis_laporan_search') == 'rekap' ? 'selected' : '' }}>
                                                Rekap
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row mt-2">

                                    <div class="col-lg-4">
                                        <button type="submit" class="btn btn-info w-100">
                                            <i class="ti ti-search"></i> Search
                                        </button>
                                    </div>

                                    <div class="col-lg-4">
                                        <button type="button" id="btnCetak" class="btn btn-primary w-100">
                                            <i class="ti ti-printer"></i> Cetak
                                        </button>
                                    </div>

                                    <div class="col-lg-4">
                                        <button type="button" id="btnExport" class="btn btn-success w-100">
                                            <i class="ti ti-download"></i> Export
                                        </button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive mt-3">

                                <table class="table table-bordered table-striped table-hover">

                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kendaraan</th>
                                            <th>Driver</th>
                                            <th>KM Awal</th>
                                            <th>KM Akhir</th>
                                            <th>Jarak</th>
                                            <th>Liter</th>
                                            <th>Rasio</th>
                                            <th>Harga</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($bbm as $d)
                                            <tr>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ $d->no_polisi }}</td>
                                                <td>{{ $d->nama_driver_helper }}</td>
                                                <td>{{ number_format($d->kilometer_awal) }}</td>
                                                <td>{{ number_format($d->kilometer_akhir) }}</td>
                                                <td>{{ number_format($d->jarak_tempuh) }}</td>
                                                <td>{{ number_format($d->jumlah_liter, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ number_format($d->rasio_bbm, 2) }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($d->jumlah_harga) }}</td>

                                                <td>
                                                    @php
                                                        $isExpired =
                                                            \Carbon\Carbon::parse($d->tanggal)->diffInDays(now()) >= 2;
                                                        $isAdminDelete = auth()->id() == 29;
                                                    @endphp

                                                    <div class="d-flex">
                                                        {{-- EDIT --}}
                                                        @if (!$isExpired)
                                                            <a href="#" class="me-2 btnEdit"
                                                                id="{{ Crypt::encrypt($d->id) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @else
                                                            <i class="ti ti-edit text-secondary"
                                                                title="Sudah lewat 2 hari"></i>
                                                        @endif

                                                        {{-- DELETE --}}
                                                        @if (!$isExpired || $isAdminDelete)
                                                            <form method="POST" class="deleteform"
                                                                action="{{ route('bbm.delete', Crypt::encrypt($d->id)) }}">
                                                                @csrf
                                                                @method('DELETE')

                                                                <a href="#" class="delete-confirm">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <i class="ti ti-trash text-secondary"
                                                                title="Tidak punya akses atau sudah lewat 2 hari"></i>
                                                        @endif
                                                    </div>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                            <div style="float:right">
                                {{ $bbm->links() }}
                            </div>

                        </div>
                    </div>

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

        function loadingElement() {
            return `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`;
        }

        const form = $("#frmSearchBBM");

        form.submit(function() {

            const dari = $(this).find("#dari_search").val();
            const sampai = $(this).find("#sampai_search").val();

            var start = new Date(dari);
            var end = new Date(sampai);

            if (dari == "") {
                Swal.fire("Oops!", "Periode Dari Harus Diisi !", "warning");
                return false;

            } else if (sampai == "") {
                Swal.fire("Oops!", "Periode Sampai Harus Diisi !", "warning");
                return false;

            } else if (start.getTime() > end.getTime()) {
                Swal.fire("Oops!", "Periode Tidak Valid", "warning");
                return false;
            }
        });

        $("#btnSearch").click(function() {
            $("#frmSearchBBM").attr("action", "{{ route('worksheetom.bbm') }}");
            $("#frmSearchBBM").attr("method", "GET");
            $("#frmSearchBBM").removeAttr("target");
        });

        $("#btnCetak, #btnExport").click(function() {

            $("#frmSearchBBM").attr("action", "{{ route('bbm.cetak') }}");
            $("#frmSearchBBM").attr("method", "POST");
            $("#frmSearchBBM").attr("target", "_blank");

            $("#frmSearchBBM").submit();

        });


        $("#btnCreate").click(function(e) {

            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data BBM");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bbm/create`);

        });


        /* EDIT */

        $(".btnEdit").click(function(e) {

            e.preventDefault();
            var id = $(this).attr("id");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Data BBM");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bbm/${id}/edit`);

        });

    });
</script>
@endpush
