@extends('layouts.app')
@section('titlepage', 'Atur Ajuan Program Ikatan')

@section('content')
@section('navigasi')
    <span>Atur Ajuan Program Ikatan</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('ajuanprogramikatan.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
                @endcan
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
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Avg Penjualan</th>
                                    <th>Qty Target</th>
                                    <th>Reward</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kode_pelanggan }}</td>
                                        <td>{{ $d->nama_pelanggan }}</td>
                                        <td class="text-end">{{ formatAngka($d->qty_avg) }}</td>
                                        <td class="text-end">{{ formatAngka($d->qty_target) }}</td>
                                        <td class="text-end">{{ formatAngka($d->reward) }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @can('ajuanprogramikatan.edit')
                                                    <a href="#" kode_pelanggan = "{{ Crypt::encrypt($d->kode_pelanggan) }}" class="btnEdit me-1">
                                                        <i class="ti ti-edit text-success"></i>
                                                    </a>
                                                @endcan
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
                    if (response.data === null) {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Memiliki Histori Transaksi!",
                            icon: "warning",
                            showConfirmButton: true
                        });
                        return;
                    }
                    $("#modalPelanggan").modal("hide");
                    $(document).find("input[name='qty_avg']").val(response.data.qty);
                    $(document).find("input[name='nama_pelanggan']").val(response.data.nama_pelanggan);
                    $(document).find("input[name='kode_pelanggan']").val(response.data.kode_pelanggan);
                }
            });
        }
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_program = "{{ Crypt::encrypt($programikatan->kode_program) }}";
            getavgPelanggan(kode_pelanggan, kode_program);

        });


        $(document).on('submit', '#formAddpelanggan, #formEditpelanggan', function(e) {
            // e.preventDefault();
            let kode_pelanggan = $(this).find("input[name='kode_pelanggan']").val();
            let target = $(this).find("input[name='target']").val();
            let reward = $(this).find("input[name='reward']").val();

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
            } else {
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
            $("#loadmodal").load("/ajuanprogramikatan/" + no_pengajuan + "/" + kode_pelanggan + "/edit");

        });
    });
</script>
@endpush
