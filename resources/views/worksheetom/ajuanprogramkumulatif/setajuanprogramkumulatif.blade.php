@extends('layouts.app')
@section('titlepage', 'Atur Ajuan Program Kumulatif')

@section('content')
@section('navigasi')
    <span>Atur Ajuan Program Kumulatif</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ajuankumulatif.index') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                    @can('ajuankumulatif.create')
                        @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $programkumulatif->rsm == null)
                            @if ($programkumulatif->status == 0)
                                <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                            @endif
                        @endif

                        @if ($user->hasRole('super admin'))
                            <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
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
                                <td class="text-end">{{ $programkumulatif->no_pengajuan }}</td>
                            </tr>
                            <tr>
                                <th>No. Dokumen</th>
                                <td class="text-end">{{ $programkumulatif->nomor_dokumen }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($programkumulatif->tanggal) }}</td>
                            </tr>

                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $programkumulatif->kode_cabang }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Pembayaran</th>
                                    <th>No. Rekening</th>
                                    <th>Pemilik</th>
                                    <th>Bank</th>
                                    <th>Doc</th>
                                    <th>#</th>
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
                                        <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                                        <td>{{ $d->no_rekening }}</td>
                                        <td>{{ $d->pemilik_rekening }}</td>
                                        <td>{{ $d->bank }}</td>
                                        <td>
                                            @if ($d->file_doc != null)
                                                <a href="{{ asset('storage/ajuanprogramkumulatif/' . $d->file_doc) }}" target="_blank">
                                                    <i class="ti ti-file-text"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('ajuankumulatif.cetakkesepakatan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}"
                                                    target="_blank" class="me-1">
                                                    <i class="ti ti-printer text-primary"></i>
                                                </a>
                                                @if ($programkumulatif->status == 1)
                                                @endif
                                                @can('ajuankumulatif.edit')
                                                    <a href="#" kode_pelanggan = "{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnEdit me-1">
                                                        <i class="ti ti-edit text-success"></i>
                                                    </a>
                                                @endcan
                                                @if ($programkumulatif->status == 0)
                                                    @can('ajuankumulatif.delete')
                                                        @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $d->rsm == null)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuankumulatif.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @elseif($user->hasRole('regional sales manager') && $d->gm == null)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuankumulatif.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @elseif($user->hasRole('gm marketing') && $d->direktur == null)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuankumulatif.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @elseif($user->hasRole(['super admin', 'direktur']))
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ajuankumulatif.deletepelanggan', [Crypt::encrypt($d->no_pengajuan), Crypt::encrypt($d->kode_pelanggan)]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
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
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
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
            let no_pengajuan = "{{ Crypt::encrypt($programkumulatif->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/ajuankumulatif/" + no_pengajuan + "/tambahpelanggan");
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
            ajax: "{{ route('pelanggan.getpelanggancabangjson', $programkumulatif->kode_cabang) }}",
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



        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let nama_pelanggan = $(this).attr('nama_pelanggan');

            $(document).find("input[name='nama_pelanggan']").val(nama_pelanggan);
            $(document).find("input[name='kode_pelanggan']").val(kode_pelanggan);
            $("#modalPelanggan").modal("hide");
        });


        $(document).on('submit', '#formAddpelanggan, #formEditpelanggan', function(e) {
            // e.preventDefault();
            let kode_pelanggan = $(this).find("input[name='kode_pelanggan']").val();
            let target = $(this).find("input[name='target']").val();
            let reward = $(this).find("input[name='reward']").val();
            let budget = $(this).find("select[name='budget']").val();
            let metode_pembayaran = $(this).find("select[name='metode_pembayaran']").val();
            let file_doc = $(this).find("input[name='file_doc']").val();

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
            } else if (budget == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Budget harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#budget").focus();
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
            let no_pengajuan = "{{ Crypt::encrypt($programkumulatif->no_pengajuan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Pelanggan");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadmodal").load("/ajuankumulatif/" + no_pengajuan + "/" + kode_pelanggan + "/editpelanggan");

        });
    });
</script>
@endpush
