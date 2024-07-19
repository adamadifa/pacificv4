@extends('layouts.app')
@section('titlepage', 'Atur Jadwal')

@section('content')
@section('navigasi')
    <span class="text-muted">Jadwal Shift</span> / <span>Atur Jadwal {{ DateToIndo($jadwalshift->dari) }} s/d {{ DateToIndo($jadwalshift->sampai) }}</span>
@endsection
<div class="row mb-3">
    <div class="col">
        <div class="d-flex justify-content-end">
            <button class="btn btn-warning" id="gantishift" kode_jadwalshift="{{ Crypt::encrypt($jadwalshift->kode_jadwalshift) }}"><i
                    class="ti ti-refresh me-1"></i>Ganti
                Shift</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary aturshift" shift="1"><i class="ti ti-plus me-1"></i>Atur Shift 1</button>
            </div>
            <div class="card-body">
                <table class="table  table-hover table-striped" id="tabelshift1">
                    <thead class="table-dark">
                        <tr>
                            <th>Nik</th>
                            <th>Nama Karyawan</th>
                            <th>Grup</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="loadshift1">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary aturshift" shift="2"><i class="ti ti-plus me-1"></i>Atur Shift 2</button>
            </div>
            <div class="card-body">
                <table class="table  table-hover table-striped" id="tabelshift1">
                    <thead class="table-dark">
                        <tr>
                            <th>Nik</th>
                            <th>Nama Karyawan</th>
                            <th>Grup</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="loadshift2">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-xs-12">

        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary aturshift" shift="3"><i class="ti ti-plus me-1"></i>Atur Shift 3</button>
            </div>
            <div class="card-body">
                <table class="table  table-hover table-striped" id="tabelshift1">
                    <thead class="table-dark">
                        <tr>
                            <th>Nik</th>
                            <th>Nama Karyawan</th>
                            <th>Grup</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="loadshift3">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };




        function loadshift(shift, kode_jadwal) {
            let kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            $("#loadshift" + shift).html(`<tr><td colspan="3" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: '/jadwalshift/getshift',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwal: kode_jadwal,
                    kode_jadwalshift: kode_jadwalshift
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    $("#loadshift" + shift).html(respond);
                }
            });
        }

        loadshift(1, "JD002");
        loadshift(2, "JD003");
        loadshift(3, "JD004");


        $(".aturshift").click(function(e) {
            e.preventDefault();
            var shift = $(this).attr('shift');
            var kode_jadwalshift = "{{ Crypt::encrypt($jadwalshift->kode_jadwalshift) }}";
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Atur Shift " + shift);
            $("#loadmodal").load(`/jadwalshift/${shift}/${kode_jadwalshift}/aturshift`);
        });


        $(document).on('click', '.delete', function(e) {
            const nik = $(this).attr('nik');
            const kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            Swal.fire({
                title: 'Hapus Shift?',
                icon: 'warning',
                text: 'Anda Yakin Menghapus Karyawan  dari Shift Ini ?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `/jadwalshift/deleteshift`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            nik: nik,
                            kode_jadwalshift: kode_jadwalshift
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                loadshift(1, "JD002");
                                loadshift(2, "JD003");
                                loadshift(3, "JD004");
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

        $("#gantishift").click(function(e) {
            e.preventDefault();
            const kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Ganti Shift");
            $("#loadmodal").load(`/jadwalshift/${kode_jadwalshift}/gantishift`);
        });


    });
</script>
@endpush
