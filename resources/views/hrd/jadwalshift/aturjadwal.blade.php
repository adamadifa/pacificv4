@extends('layouts.app')
@section('titlepage', 'Atur Jadwal')

@section('content')
@section('navigasi')
    <span class="text-muted">Jadwal Shift</span> / <span>Atur Jadwal {{ DateToIndo($jadwalshift->dari) }} s/d {{ DateToIndo($jadwalshift->sampai) }}</span>
@endsection
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
                        </tr>
                    </thead>
                    <tbody id="loadshift3">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
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
            $("#modal").find(".modal-dialog").addClass("modal-lg");
        });






    });
</script>
@endpush
