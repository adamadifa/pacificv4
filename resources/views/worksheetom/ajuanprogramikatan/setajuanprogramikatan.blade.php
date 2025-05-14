@extends('layouts.app')
@section('titlepage', 'Atur Ajuan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Ajuan Program Ikatan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ajuanprogramikatan.index') }}" class="me-1 btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                    @can('ajuanprogramikatan.create')
                        @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $programikatan->rsm == null)
                            @if ($programikatan->status == 0)
                                <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i>
                                    Tambah Pelanggan</a>
                            @endif
                        @endif

                        @if ($user->hasRole('super admin'))
                            <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i>
                                Tambah Pelanggan</a>
                        @endif
                    @endcan
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>No. Pengajuan</th>
                                <td class="text-end">{{ $programikatan->no_pengajuan }}</td>
                            </tr>
                            <tr>
                                <th>No. Dokumen</th>
                                <td class="text-end">{{ $programikatan->nomor_dokumen }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($programikatan->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Periode Penjualan</th>
                                <td class="text-end">{{ DateToIndo($programikatan->periode_dari) }} s.d
                                    {{ DateToIndo($programikatan->periode_sampai) }}</td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td class="text-end">{{ $programikatan->nama_program }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $programikatan->kode_cabang }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2" style="width: 15%">Nama </th>
                                    <th rowspan="2" class="text-center">TOTAL<br>PENJUALAN </th>
                                    <th rowspan="2" class="text-center">Target</th>
                                    <th rowspan="2" class="text-center">%</th>
                                    <th rowspan="2">Reward</th>
                                    <th rowspan="2">TOP</th>
                                    <th colspan="3">Budget</th>
                                    <th rowspan="2">PMB</th>
                                    <th rowspan="2">Pencairan</th>
                                    <th rowspan="2">Doc</th>
                                    <th rowspan="2">#</th>
                                </tr>
                                <tr>
                                    <th>SMM</th>
                                    <th>RSM</th>
                                    <th>GM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $metode_pembayaran = [
                                        'TN' => 'Tunai',
                                        'TF' => 'Transfer',
                                        'VC' => 'Voucher',
                                    ];
                                @endphp
                                @foreach ($detail as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kode_pelanggan }}</td>
                                        <td>{{ $d->nama_pelanggan }}</td>
                                        <td class="text-center">{{ formatAngka($d->qty_rata_rata) }} </td>
                                        <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                                        <td class="text-end">
                                            @php
                                                $kenaikan = $d->qty_target - ROUND($d->qty_rata_rata);
                                                $persentase =
                                                    $d->qty_rata_rata == 0
                                                        ? 0
                                                        : ($kenaikan / ROUND($d->qty_rata_rata)) * 100;
                                                $persentase = formatAngkaDesimal($persentase);
                                            @endphp
                                            {{ $persentase }}%
                                        </td>
                                        <td class="text-end">{{ formatAngka($d->reward) }}</td>
                                        <td class="text-end">{{ $d->top }}</td>
                                        <td class="text-end">{{ formatAngka($d->budget_smm) }}</td>
                                        <td class="text-end">{{ formatAngka($d->budget_rsm) }}</td>
                                        <td class="text-end">{{ formatAngka($d->budget_gm) }}</td>
                                        <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                                        <td class="text-end">{{ formatAngka($d->periode_pencairan) }} Bulan</td>
                                        {{-- <td>{{ $d->no_rekening }}</td>
                                        <td>{{ $d->pemilik_rekening }}</td>
                                        <td>{{ $d->bank }}</td> --}}
                                        <td>
                                            @if ($d->file_doc != null)
                                                <a href="{{ asset('storage/ajuanprogramikatan/' . $d->file_doc) }}"
                                                    target="_blank">
                                                    <i class="ti ti-file-text" class="me-1"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('ajuanprogramikatan.cetakkesepakatan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}"
                                                    target="_blank" class="me-1">
                                                    <i class="ti ti-printer text-primary"></i>
                                                </a>
                                                @can('ajuanprogramikatan.edit')
                                                    @if ($programikatan->status == 0)
                                                        <a href="#"
                                                            kode_pelanggan = "{{ Crypt::encrypt($d->kode_pelanggan) }}"
                                                            class="btnEdit me-1">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                                @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $d->rsm == null)
                                                    @if ($programikatan->status == 0)
                                                        @can('ajuanprogramikatan.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuanprogramikatan.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                @elseif ($user->hasRole('regional sales manager') && $d->gm == null)
                                                    @if ($programikatan->status == 0)
                                                        @can('ajuanprogramikatan.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuanprogramikatan.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                @elseif ($user->hasRole('gm marketing') && $d->direktur == null)
                                                    @if ($programikatan->status == 0)
                                                        @can('ajuanprogramikatan.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuanprogramikatan.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                @elseif($user->hasRole(['super admin', 'direktur', 'regional sales manager', 'gm marketing']))
                                                    @if ($programikatan->status == 0)
                                                        @can('ajuanprogramikatan.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuanprogramikatan.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let no_pengajuan = "{{ Crypt::encrypt($programikatan->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/ajuanprogramikatan/" + no_pengajuan + "/tambahpelanggan");
        });

        $(document).on('click', '#kode_pelanggan_search', function(e) {
            e.preventDefault();
            $("#modalPelanggan").modal("show");

        });

        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: "{{ route('pelanggan.getpelanggancabangjson', $programikatan->kode_cabang) }}",
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });


        //Get Pelanggan
        function getavgPelanggan(kode_pelanggan, kode_program) {

            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/${kode_program}/getavgpelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    if (response.type === 2) {
                        $("#modalPelanggan").modal("hide");
                        $(document).find("input[name='qty_avg']").val(0);
                        $(document).find("input[name='nama_pelanggan']").val(response.data
                            .nama_pelanggan);
                        $(document).find("input[name='kode_pelanggan']").val(response.data
                            .kode_pelanggan);
                        return;
                    }
                    $("#modalPelanggan").modal("hide");
                    $(document).find("input[name='qty_avg']").val(Math.round(response.data.qty));
                    $(document).find("input[name='nama_pelanggan']").val(response.data
                        .nama_pelanggan);
                    $(document).find("input[name='kode_pelanggan']").val(response.data
                        .kode_pelanggan);
                }
            });
        }

        function gethistoripelangganprogram(kode_pelanggan, kode_program) {

            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/${kode_program}/gethistoripelangganprogram`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $("#gethistoripelangganprogram").html(response);
                }
            });
        }
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_program = "{{ Crypt::encrypt($programikatan->kode_program) }}";
            let nama_pelanggan = $(this).attr('nama_pelanggan');

            getavgPelanggan(kode_pelanggan, kode_program);
            gethistoripelangganprogram(kode_pelanggan, kode_program);
            $(document).find("input[name='nama_pelanggan']").val(nama_pelanggan);
            $(document).find("input[name='kode_pelanggan']").val(kode_pelanggan);
            $("#modalPelanggan").modal("hide");

        });


        $(document).on('submit', '#formAddpelanggan, #formEditpelanggan', function(e) {
            // e.preventDefault();
            let kode_pelanggan = $(this).find("input[name='kode_pelanggan']").val();
            let target = $(this).find("input[name='target']").val();
            let reward = $(this).find("input[name='reward']").val();
            let budget_smm = $(this).find("input[name='budget_smm']").val();
            let bugdet_rsm = $(this).find("input[name='budget_rsm']").val();
            let budget_gm = $(this).find("input[name='budget_gm']").val();

            let metode_pembayaran = $(this).find("select[name='metode_pembayaran']").val();
            let file_doc = $(this).find("input[name='file_doc']").val();
            let top = $(this).find("select[name='top']").val();

            let gradTotaltarget = $(this).find("#gradTotaltarget").text();

            let targetValue = target.replace(/\./g, '');
            let gradTotaltargetValue = gradTotaltarget.replace(/\./g, '');

            let periode_pencairan = $(this).find("select[name='periode_pencairan']").val();

            let tipe_reward = $(this).find("select[name='tipe_reward']").val();
            if (kode_pelanggan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pelanggan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_pelanggan").focus();
                    },
                });
                return false;
            } else if (target == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Target harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#target").focus();
                    },
                });
                return false;
            } else if (gradTotaltargetValue != targetValue) {
                Swal.fire({
                    title: "Oops!",
                    text: "Target harus sama dengan Total Target !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#target").focus();
                    },
                });
                return false;
            } else if (reward == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Reward harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#reward").focus();
                    },
                });
                return false;
            } else if (gradTotaltargetValue != targetValue) {
                Swal.fire({
                    title: "Oops!",
                    text: "Target harus sama dengan Total Target !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#target").focus();
                    },
                });
                return false;
            } else if (top == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Top harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#top").focus();
                    }
                });
                return false;
            } else if (periode_pencairan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Pencairan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#periode_pencarian").focus();
                    }
                });
                return false;
            } else if (tipe_reward == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tipe Reward harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#periode_pencarian").focus();
                    }
                });
                return false;
            } else if (budget_smm == "" && bugdet_rsm == "" && budget_gm == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Budget harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#budget_smm").focus();
                    },
                });
                return false;
            } else if (metode_pembayaran == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Metode Pembayaran harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#metode_pembayaran").focus();
                    },
                });
                return false;
            } else {
                let fileDoc = $(this).find("#file_doc")[0].files[0];
                if (fileDoc) {
                    if (fileDoc.type !== 'application/pdf') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Format file harus PDF !",
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: () => {
                                $(this).find("#file_doc").focus();
                            },
                        });
                        return false;
                    } else if (fileDoc.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            title: "Oops!",
                            text: "Ukuran file maksimal 2 MB !",
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: () => {
                                $(this).find("#file_doc").focus();
                            },
                        });
                        return false;
                    }
                }
                $(this).find("#btnSimpan").prop('disabled', true);
                $(this).find("#btnSimpan").html(` <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
            }
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let no_pengajuan = "{{ Crypt::encrypt($programikatan->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Target Pelanggan");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/ajuanprogramikatan/" + no_pengajuan + "/" + kode_pelanggan +
                "/edit");

        });
    });
</script>
@endpush
