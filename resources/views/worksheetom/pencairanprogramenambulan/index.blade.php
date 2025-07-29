@extends('layouts.app')
@section('titlepage', 'Pencairan Program Enambulan')

@section('content')
@section('navigasi')
    <span>Pencairan Program Enambulan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            @include('layouts.navigation_program_ikatan_enambulan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pencairanprogramenambulan.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pencairanprogramenambulan.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                {{-- <x-input-with-icon label="No. Dokumen" value="{{ Request('nomor_dokumen') }}" name="nomor_dokumen"
                                    icon="ti ti-barcode" /> --}}
                                <x-select label="Semua Program" name="kode_program" :data="$programikatan" key="kode_program"
                                    textShow="nama_program" select2="select2Kodeprogram" upperCase="true"
                                    selected="{{ Request('kode_program') }}" />
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved"
                                            {{ Request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected"
                                            {{ Request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
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
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" valign="middle">No. Ajuan</th>
                                            <th rowspan="2" valign="middle">Tanggal</th>
                                            <th rowspan="2" valign="middle">Semester</th>
                                            <th rowspan="2" valign="middle">Tahun</th>
                                            <th rowspan="2" valign="middle">Program</th>
                                            <th rowspan="2" valign="middle">Cabang</th>
                                            {{-- <th rowspan="2" valign="middle">Periode</th> --}}
                                            <th colspan="4" class="text-center">Persetujuan</th>

                                            <th rowspan="2" valign="middle">Status</th>
                                            <th rowspan="2" valign="middle">Keuangan</th>
                                            <th rowspan="2" valign="middle"><i class="ti ti-file-description"></i>
                                            </th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">OM</th>
                                            <th class="text-center">RSM</th>
                                            <th class="text-center">GM</th>
                                            <th class="text-center">Direktur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pencairanprogramenambulan as $d)
                                            <tr>
                                                <td>{{ $d->kode_pencairan }}</td>
                                                <td>{{ $d->tanggal }}</td>
                                                <td>{{ $d->semester }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ $d->nama_program }}</td>
                                                <td>{{ strtoUpper($d->nama_cabang) }}</td>
                                                {{-- <td>{{ $namabulan[$d->bulan] }} {{ $d->tahun }}</td> --}}
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->om))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->rsm))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->gm))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->direktur))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ($d->status == '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @elseif ($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->keuangan == null)
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-square-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($d->bukti_transfer))
                                                        <a href="{{ url($d->bukti_transfer) }}" target="_blank">
                                                            <i class="ti ti-receipt text-success"></i>
                                                        </a>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('ajuanprogramikatan.approve')
                                                            @if ($user->hasRole('operation manager') && $d->rsm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('regional sales manager') && $d->gm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('gm marketing') && $d->direktur == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole(['manager keuangan', 'staff keuangan']) && $d->status == 1)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole(['super admin', 'direktur']) && $d->gm != null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        @can('pencairanprogramenambulan.edit')
                                                            <a href="{{ route('pencairanprogramenambulan.setpencairan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="me-1">
                                                                <i class="ti ti-settings text-primary"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogramenambulan.show')
                                                            <a href="{{ route('pencairanprogramenambulan.cetak', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-printer text-success"></i>
                                                            </a>
                                                            <a href="{{ route('pencairanprogramenambulan.cetak', Crypt::encrypt($d->kode_pencairan)) }}?export=true"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-download text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogramenambulan.upload')
                                                            <a href="#"
                                                                kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}"class="btnUpload">
                                                                <i class="ti ti-upload text-primary"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogramenambulan.delete')
                                                            @if ($user->hasRole(['operation manager', 'sales marketing manager', 'super admin']) && $d->rsm == null)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('pencairanprogramenambulan.delete', Crypt::encrypt($d->kode_pencairan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
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
                                {{ $pencairanprogramenambulan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-fullscreen" show="loadmodalapprove" title="" />
<x-modal-form id="modalajuanProgram" size="modal-xl" show="loadmodalajuanProgram" title="Ajuan Program Ikatan" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<x-modal-form id="modalUpload" size="" show="loadmodalupload" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Pencairan Program Ikatan Enambulan");
            $("#loadmodal").load("/pencairanprogramenambulan/create");
        });
        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogramenambulan/" + kode_pencairan + "/upload");
        });
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(document).on('click', '#no_pengajuan_search', function(e) {
            e.preventDefault();
            $("#modalajuanProgram").modal("show");
            $("#loadmodalajuanProgram").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodalajuanProgram").load("/ajuanprogramenambulan/getajuanprogramenambulan");

        });

        $(document).on('click', '.pilihajuan', function(event) {
            var rowData = $(this).closest('tr').find('td');
            var noPengajuan = rowData.eq(1).text(); // No Pengajuan
            var noDokumen = rowData.eq(2).text(); // No. Dokumen
            var tanggal = rowData.eq(3).text(); // Tanggal
            var program = rowData.eq(4).text(); // Program
            var cabang = rowData.eq(5).text(); // Cabang
            var periode = rowData.eq(6).text(); // Periode

            var periodeSplit = periode.split("/");
            var tanggal1 = periodeSplit[0];
            var tanggal2 = periodeSplit[1];

            var bulanTahun1 = tanggal1.split("-");
            var bulan1 = bulanTahun1[1];
            var tahun1 = bulanTahun1[2];

            var bulanTahun2 = tanggal2.split("-");
            var bulan2 = bulanTahun2[1];
            var tahun2 = bulanTahun2[2];


            if (parseInt(bulan2) < parseInt(bulan1)) {
                bulan2 = parseInt(bulan2) + 12;
            }

            var bln = bulan1 * 1;
            var thn = tahun1;


            var namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                "Agustus", "September", "Oktober",
                "November", "Desember"
            ];
            $(document).find("#periodepencairan").empty();
            for (var i = parseInt(bulan1); i <= parseInt(bulan2); i++) {
                if (i > 12) {
                    bln = i - 12;
                    thn = parseInt(tahun1) + 1;
                }
                $(document).find("#periodepencairan").append(
                    `<option value="${bln}-${thn}">${namaBulan[bln]} ${thn}</option>`);
                console.log("Bulan : " + bln + " Tahun : " + thn);
                bln++;
            }
            // alert(tanggal1);

            // Lakukan sesuatu dengan data yang diambil, misalnya menampilkan di modal
            console.log(noPengajuan, noDokumen, tanggal, program, cabang, periode);
            $(document).find("#tabeldataajuan").find(
                "#no_pengajuan_text").text(noPengajuan);
            $(document).find("#tabeldataajuan").find("#nomor_dokumen").text(noDokumen);
            $(
                document).find("#tabeldataajuan").find("#tanggal").text(tanggal);
            $(document).find("#tabeldataajuan").find(
                "#nama_program").text(program);
            $(document).find("#tabeldataajuan").find("#nama_cabang").text(cabang);
            $(document).find(
                "#tabeldataajuan").find("#periode").text(periode);
            $(document).find("#no_pengajuan").val(noPengajuan);
            $(
                "#modalajuanProgram").modal("hide");
        });

        $(".btnApprove").click(function(e) {
            const kode_pencairan = $(this).attr('kode_pencairan');
            e.preventDefault();
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Pencairan Program Ikatan Enambulan");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/pencairanprogramenambulan/' + kode_pencairan + '/approve');
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = $(this).attr('kode_pencairan');
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogramenambulan/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });
    });
</script>
@endpush
