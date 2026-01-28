@extends('layouts.app')
@section('titlepage', 'Atur Ajuan Program Ikatan 2026')

@section('content')
@section('navigasi')
    <span>Atur Ajuan Program Ikatan 2026</span>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Toolbar & Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('programikatan2026.index') }}" class="btn btn-label-danger">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
            @can('programikatan2026.create')
                @if (($user->hasRole(['operation manager', 'sales marketing manager']) && $programikatan->rsm == null) || $user->hasRole(['super admin', 'regional operation manager']))
                     @if ($programikatan->status == 0)
                        <a href="#" id="btnCreate" class="btn btn-primary shadow-sm">
                            <i class="ti ti-user-plus me-1"></i> Tambah Pelanggan
                        </a>
                    @endif
                @endif
            @endcan
        </div>

        {{-- Info Card --}}
        <div class="card shadow-sm border mb-4">
            <div class="card-body p-4">
                <div class="row g-4 text-nowrap">
                    <div class="col-md-4 border-end">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-file-description fs-2 text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">No. Pengajuan</small>
                                <h6 class="mb-0 fw-bold">{{ $programikatan->no_pengajuan }}</h6>
                                <small class="text-secondary">{{ $programikatan->nomor_dokumen }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 border-end">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-files fs-2 text-info me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Program & Cabang</small>
                                <h6 class="mb-0 fw-bold text-truncate" title="{{ $programikatan->nama_program }}">{{ $programikatan->nama_program }}</h6>
                                <span class="badge bg-label-info mt-1">{{ $programikatan->kode_cabang }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                             <i class="ti ti-calendar-event fs-2 text-warning me-3"></i>
                            <div>
                                <small class="text-muted d-block mb-1">Periode</small>
                                <h6 class="mb-0 fw-bold">{{ DateToIndo($programikatan->periode_dari) }} - {{ DateToIndo($programikatan->periode_sampai) }}</h6>
                                <small class="text-muted">Tanggal: {{ DateToIndo($programikatan->tanggal) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Daftar Pelanggan</h6>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="text-white" style="background-color: #002e65;">
                        <tr>
                            <th class="fw-bold text-white">No</th>
                            <th class="fw-bold text-white">Kode</th>
                            <th class="fw-bold text-white">Nama Pelanggan</th>
                            <th class="fw-bold text-white text-center">Rata-Rata</th>
                            <th class="fw-bold text-white text-center">Target (Tambahan)</th>
                            <th class="fw-bold text-white text-center">Total</th>
                            <th class="fw-bold text-white text-end">Ach (%)</th>
                            <th class="fw-bold text-white text-end">TOP</th>
                            <th class="fw-bold text-white">Metode</th>
                            <th class="fw-bold text-white text-end">Pencairan</th>
                            <th class="fw-bold text-white text-center">Doc</th>
                            <th class="fw-bold text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
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
                                <td><span class="fw-semibold">{{ $d->kode_pelanggan }}</span></td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_avg) }}</td>
                                <td class="text-center"><span class="badge bg-label-primary">{{ formatAngka($d->qty_target) }}</span></td>
                                <td class="text-center">{{ formatAngka($d->qty_avg + $d->qty_target) }}</td>
                                <td class="text-end">
                                    @php
                                        // $kenaikan = $d->qty_target - ROUND($d->qty_rata_rata);
                                        // $persentase = $d->qty_rata_rata == 0 ? 0 : ($kenaikan / ROUND($d->qty_rata_rata)) * 100;
                                        $kenaikan = $d->qty_target;
                                        $persentase = $d->qty_avg == 0 ? 0 : ($kenaikan / $d->qty_avg) * 100;
                                        $persentase = formatAngkaDesimal($persentase);
                                        $color = $persentase >= 0 ? 'success' : 'danger';
                                    @endphp
                                    <span class="text-{{ $color }} fw-bold">{{ $persentase }}%</span>
                                </td>
                                <td class="text-end">{{ $d->top }}</td>
                                <td>{{ $metode_pembayaran[$d->metode_pembayaran] ?? $d->metode_pembayaran }}</td>
                                <td class="text-end">{{ formatAngka($d->periode_pencairan) }} Bulan</td>
                                <td class="text-center">
                                    @if ($d->file_doc != null)
                                        <a href="{{ asset('storage/programikatan2026/' . $d->file_doc) }}" target="_blank" class="text-info" data-bs-toggle="tooltip" title="Lihat Dokumen">
                                            <i class="ti ti-file-text fs-4"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('programikatan2026.cetakkesepakatan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}"
                                            target="_blank" class="text-secondary" data-bs-toggle="tooltip" title="Cetak Kesepakatan">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                        
                                        @can('programikatan2026.edit')
                                            @if ($programikatan->status == 0)
                                                <a href="#" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                 
                                            @endif
                                        @endcan
                                        <a href="#" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnUpload text-success" data-bs-toggle="tooltip" title="Upload Dokumen">
                                                    <i class="ti ti-upload"></i>
                                                </a>

                                        @if ($programikatan->status == 0)
                                            @can('programikatan2026.delete')
                                                @if (($user->hasRole(['operation manager', 'sales marketing manager']) && $d->rsm == null) ||
                                                    ($user->hasRole('regional sales manager') && $d->gm == null) ||
                                                    ($user->hasRole('gm marketing') && $d->direktur == null) ||
                                                    ($user->hasRole(['super admin', 'direktur'])))
                                                    
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('programikatan2026.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
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
        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
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
            $("#loadmodal").load("/programikatan2026/" + no_pengajuan + "/tambahpelanggan");
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
            ajax: "{{ route('programikatan2026.getpelangganjson', Crypt::encrypt($programikatan->no_pengajuan)) }}",
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
                url: `/programikatan2026/${kode_pelanggan}/${kode_program}/getavgpelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    if (response.type === 2) {
                        $("#modalPelanggan").modal("hide");
                        $(document).find("input[name='qty_avg']").val(0);
                        $(document).find(".avg-input").val(0).trigger('change');
                        $(document).find("input[name='nama_pelanggan']").val(response.data
                            .nama_pelanggan);
                        $(document).find("input[name='kode_pelanggan']").val(response.data
                            .kode_pelanggan);
                        return;
                    }
                    $("#modalPelanggan").modal("hide");
                    $(document).find("input[name='qty_avg']").val(Math.round(response.data.qty));
                    $(document).find(".avg-input").val(convertToRupiah(Math.round(response.data.qty))).trigger('change');
                    $(document).find("input[name='nama_pelanggan']").val(response.data
                        .nama_pelanggan);
                    $(document).find("input[name='kode_pelanggan']").val(response.data
                        .kode_pelanggan);
                }
            });
        }

        function gethistoripelangganprogram(kode_pelanggan, kode_program) {

            $.ajax({
                url: `/programikatan2026/${kode_pelanggan}/${kode_program}/gethistoripelangganprogram`,
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
            // let reward = $(this).find("input[name='reward']").val();
            // let budget_smm = $(this).find("input[name='budget_smm']").val();
            // let bugdet_rsm = $(this).find("input[name='budget_rsm']").val();
            // let budget_gm = $(this).find("input[name='budget_gm']").val();

            let metode_pembayaran = $(this).find("select[name='metode_pembayaran']").val();
            let file_doc = $(this).find("input[name='file_doc']").val();
            let top = $(this).find("select[name='top']").val();

            let gradTotaltarget = $(this).find("#gradTotaltarget").text();
            let grandTotaltargetpenambah = $(this).find("#grandTotaltargetpenambah").text();

            let targetValue = target.replace(/\./g, '');
            let gradTotaltargetValue = gradTotaltarget.replace(/\./g, '');
            let grandTotaltargetpenambahValue = grandTotaltargetpenambah.replace(/\./g, '');

            let periode_pencairan = $(this).find("select[name='periode_pencairan']").val();

            // let tipe_reward = $(this).find("select[name='tipe_reward']").val();
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
            } else if (grandTotaltargetpenambahValue != targetValue) {
                Swal.fire({
                    title: "Oops!",
                    text: "Total Target Tambahan harus sama dengan Total Target Per Bulan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#target").focus();
                    },
                });
                return false;
            // } else if (reward == "") {
            //     Swal.fire({
            //         title: "Oops!",
            //         text: "Reward harus diisi !",
            //         icon: "warning",
            //         showConfirmButton: true,
            //         didClose: () => {
            //             $(this).find("#reward").focus();
            //         },
            //     });
            //     return false;
            // } else if (gradTotaltargetValue != targetValue) {
            //     Swal.fire({
            //         title: "Oops!",
            //         text: "Target harus sama dengan Total Target !",
            //         icon: "warning",
            //         showConfirmButton: true,
            //         didClose: () => {
            //             $(this).find("#target").focus();
            //         },
            //     });
            //     return false;
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
            // } else if (tipe_reward == "") {
            //     Swal.fire({
            //         title: "Oops!",
            //         text: "Tipe Reward harus diisi !",
            //         icon: "warning",
            //         showConfirmButton: true,
            //         didClose: () => {
            //             $(this).find("#periode_pencarian").focus();
            //         }
            //     });
            //     return false;
            // } else if (budget_smm == "" && bugdet_rsm == "" && budget_gm == "") {
            //     Swal.fire({
            //         title: "Oops!",
            //         text: "Budget harus diisi !",
            //         icon: "warning",
            //         showConfirmButton: true,
            //         didClose: () => {
            //             $(this).find("#budget_smm").focus();
            //         },
            //     });
            //     return false;
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
            $("#loadmodal").load("/programikatan2026/" + no_pengajuan + "/" + kode_pelanggan +
                "/editpelanggan");

        });

        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let no_pengajuan = "{{ Crypt::encrypt($programikatan->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Upload Dokumen Kesepakatan");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/programikatan2026/" + no_pengajuan + "/" + kode_pelanggan +
                "/uploadfile");
        });
    });
</script>
@endpush
