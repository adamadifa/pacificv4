@extends('layouts.app')
@section('titlepage', 'Atur Pencairan Program')

@section('content')
@section('navigasi')
    <span>Atur Pencairan Program</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('pencairanprogram.index') }}" class="btn btn-danger"><i class="fa fa-arrow-left me-2"></i> Kembali</a>
                    @can('pencairanprogram.create')
                        @if ($user->hasRole(['operation manager', 'sales marketing manager']) && $pencairanprogram->rsm == null)
                            <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Pelanggan</a>
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
                                <th>Kode Pencairan</th>
                                <td class="text-end">{{ $pencairanprogram->kode_pencairan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($pencairanprogram->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Periode Penjualan</th>
                                <td class="text-end">{{ $namabulan[$pencairanprogram->bulan] }} {{ $pencairanprogram->tahun }}</td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td class="text-end">{{ $pencairanprogram->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $pencairanprogram->kode_cabang }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="text-center" valign="middle">Nik</th>
                                    <th rowspan="2" class="text-center" valign="middle">Kode Pel</th>
                                    <th rowspan="2" valign="middle">Nama Pelanggan</th>
                                    <th rowspan="2" class="text-center" valign="middle">Qty</th>
                                    <th colspan="2" class="text-center" valign="middle">Diskon</th>
                                    <th rowspan="2" class="text-center" valign="middle">Cashback</th>
                                    <th rowspan="2" class="text-center" valign="middle">T/TF/VC</th>
                                    <th rowspan="2" class="text-center" valign="middle">No. Rek</th>
                                    <th rowspan="2" class="text-center" valign="middle">Pemilik</th>
                                    <th rowspan="2" class="text-center" valign="middle">Bank</th>
                                    <th rowspan="2" class="text-center" valign="middle">#</th>
                                </tr>
                                <tr>
                                    <th>Reguler</th>
                                    <th>Kumulatif</th>
                                </tr>
                            </thead>
                            <tbody id="loaddetailpencairan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Pelanggan");
            $("#loadmodal").load("/pencairanprogram/" + kode_pencairan + "/tambahpelanggan");

        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            let top = $(this).attr('top');
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogram/${kode_pelanggan}/${kode_pencairan}/${top}/detailfaktur`);
        });


        function getdetailpencairan() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            // $("#loaddetailpencairan").html("<tr class='text-center'><td colspan='5'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getdetailpencairan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pencairan: kode_pencairan
                },
                cache: false,
                success: function(data) {
                    $("#loaddetailpencairan").html(data);
                }
            });
        }

        getdetailpencairan();

        function loadpenjualanpelanggan() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            // $("#loadpenjualanpelanggan").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getpelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pencairan: kode_pencairan
                },
                cache: false,
                success: function(data) {
                    $("#loadpenjualanpelanggan").html(data);
                }
            })
        }

        function loadpenjualanpelanggantop30() {
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            $("#loadpenjualanpelanggantop30").html("<tr class='text-center'><td colspan='8'>Loading...</td></tr>");
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/getpelanggantop30',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pencairan: kode_pencairan
                },
                cache: false,
                success: function(data) {
                    $("#loadpenjualanpelanggantop30").html(data);
                }
            })
        }



        loadpenjualanpelanggan();
        loadpenjualanpelanggantop30();

        $(document).on('submit', '.formAddpelanggan', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '/pencairanprogram/storepelanggan',
                data: formData,
                cache: false,
                success: function(respond) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Data Berhasil Disimpan !",
                        icon: "success",
                        showConfirmButton: true,
                        didClose: (e) => {
                            getdetailpencairan();
                            loadpenjualanpelanggan();
                            loadpenjualanpelanggantop30();
                        },
                    });
                },
                error: function(respond) {
                    Swal.fire({
                        title: "Oops!",
                        text: respond.responseJSON.message,
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            getdetailpencairan();
                        },
                    });
                }
            });

        });

        $(document).on('click', '.deletedetailpencairan', function(e) {
            e.preventDefault();
            let kode_pencairan = "{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}";
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lanjutkan dengan penghapusan jika dikonfirmasi
                    $.ajax({
                        type: 'POST',
                        url: '/pencairanprogram/deletedetailpencairan',
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_pencairan: kode_pencairan,
                            kode_pelanggan: kode_pelanggan
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Data Berhasil Dihapus!",
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getdetailpencairan();
                                    loadpenjualanpelanggan();
                                },
                            });
                        },
                        error: function(respond) {
                            Swal.fire({
                                title: "Oops!",
                                text: respond.responseJSON.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getdetailpencairan();
                                },
                            });
                        }
                    });
                }
            });

        });
    });
</script>
@endpush
