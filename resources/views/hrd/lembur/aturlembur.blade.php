@extends('layouts.app')
@section('titlepage', 'Atur Lembur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Atur Lembur</h4>
            <small class="text-muted">Mengatur karyawan untuk rencana lembur.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('lembur.index') }}"><i class="ti ti-clipboard-list me-1"></i>Lembur</a>
                </li>
                <li class="breadcrumb-item active">Atur Lembur</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-settings-cog me-2"></i>Detail Lembur</h6>
                    @can('lembur.setlembur')
                        <a href="#" id="btnCreate" class="btn btn-primary btn-sm"><i class="ti ti-user-plus me-1"></i> Tambah
                            Karyawan</a>
                    @endcan
                </div>
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
                        <div class="table-responsive">
                            <table class="table table-hover table-striped border">
                                <thead style="background-color: #002e65;">
                                    <tr>
                                        <th class="text-white">Nik</th>
                                        <th class="text-white">Nama Karyawan</th>
                                        <th class="text-white">Dept</th>
                                        <th class="text-white">Grup</th>
                                        <th class="text-white text-center">#</th>
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
