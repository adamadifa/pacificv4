@extends('layouts.app')
@section('titlepage', 'Atur Lembur')

@section('content')
@section('navigasi')
    <span>Atur Lembur</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('lembur.setlembur')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah
                        Karyawan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>Kode Lembur</th>
                                <td class="text-end">{{ $lembur->kode_lembur }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($lembur->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Mulai</th>
                                <td class="text-end">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_dari)) }}</td>
                            </tr>
                            <tr>
                                <th>Selesai</th>
                                <td class="text-end">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_sampai)) }}</td>
                            </tr>
                            <tr>
                                <th>Istirahat</th>
                                <td class="text-end">
                                    @if ($lembur->istirahat == 1)
                                        <i class="ti ti-checks text-success"></i>
                                    @else
                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumla Jam</th>
                                <td class="text-end">
                                    @php
                                        $istirahat = $lembur->istirahat == 1 ? 1 : 0;
                                        $jmljam = hitungjamdesimal($lembur->tanggal_dari, $lembur->tanggal_sampai);
                                        $jmljam = $jmljam - $istirahat;
                                    @endphp
                                    {{ $jmljam }} Jam
                                </td>
                            </tr>
                            <th>Departemen</th>
                            <td class="text-end">{{ $lembur->nama_dept }}</td>
                            <tr>
                                <th>Keterangan</th>
                                <td class="text-end">{{ $lembur->keterangan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nik</th>
                                    <th>Nama Karyawan</th>
                                    <th>Dept</th>
                                    <th>Grup</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="loadlemburkaryawan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loadlemburkaryawan() {
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            $("#loadlemburkaryawan").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
            $("#loadlemburkaryawan").load(`/lembur/${kode_lembur}/getkaryawanlembur`);
        }
        loadlemburkaryawan();


        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }

        $("#btnCreate").click(function() {
            loading();
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Lembur");
            $("#loadmodal").load(`/lembur/${kode_lembur}/aturkaryawan`);
        });

        $(document).on('click', '.delete', function(e) {
            const kode_lembur = "{{ $lembur->kode_lembur }}";
            const nik = $(this).attr("nik");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `/lembur/deletekaryawanlembur`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_lembur: kode_lembur,
                            nik: nik
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                loadlemburkaryawan();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            })
        });

    });
</script>
@endpush
