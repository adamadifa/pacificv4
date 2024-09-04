@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>
            <div class="tab-content" id="tab-content-main">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" id="loadrekappersediaan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<x-modal-form id="modal" show="loadmodal" title="" size="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const formRekappenjualan = $('#formRekappenjualan');
        const formDppp = $('#formDppp');
        const formRekapkendaraan = $('#formRekapkendaraan');
        const formAup = $('#formAup');

        const select2Kodecabangrekappenjualan = $('.select2Kodecabangrekappenjualan');
        if (select2Kodecabangrekappenjualan.length) {
            select2Kodecabangrekappenjualan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodecabangAup = $('.select2KodecabangAup');
        if (select2KodecabangAup.length) {
            select2KodecabangAup.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabangrekapkendaraan = $('.select2Kodecabangrekapkendaraan');
        if (select2Kodecabangrekapkendaraan.length) {
            select2Kodecabangrekapkendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabangrekapdppp = $('.select2Kodecabangrekapdppp');
        if (select2Kodecabangrekapdppp.length) {
            select2Kodecabangrekapdppp.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }

        formRekappenjualan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
            const bulan = formRekappenjualan.find('#bulan').val();
            const tahun = formRekappenjualan.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekappenjualan.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekappenjualan.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Penjualan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekappenjualan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        formRekapkendaraan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekapkendaraan.find('#kode_cabang_rekapkendaraan').val();
            const bulan = formRekapkendaraan.find('#bulan').val();
            const tahun = formRekapkendaraan.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekapkendaraan.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formRekapkendaraan.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('Rekap Kendaraan');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapkendaraan') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        });

        formDppp.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formDppp.find('#kode_cabang_rekapdppp').val();
            const bulan = formDppp.find('#bulan').val();
            const tahun = formDppp.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formDppp.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formDppp.find('#tahun').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('DPPP');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.rekapdppp') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        })

        formAup.submit(function(e) {
            e.preventDefault();
            const kode_cbg = "{{ auth()->user()->kode_cabang }}";
            const kode_cabang = kode_cbg != 'PST' ? kode_cbg : formAup.find('#kode_cabang_aup').val();
            // alert(kode_cabang);
            const tanggal = formAup.find('#tanggal').val();
            const exclude = formAup.find('#exclude').val();
            const address = kode_cabang == "" ? "/dashboard/rekapaup" : "/dashboard/rekapaupcabang";
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formAup.find('#tanggal').focus();
                    },
                })
            } else {
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text('AUP');
                $("#modal").find(".modal-dialog").removeClass('modal-xl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxl');
                $("#modal").find(".modal-dialog").removeClass('modal-xxxl');
                $("#modal").find(".modal-dialog").addClass('modal-xxl');
                loading();
                $.ajax({
                    type: "POST",
                    url: address,
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        kode_cabang: kode_cabang,
                        exclude: exclude
                    },
                    success: function(response) {
                        $("#loadmodal").html(response);
                    }
                });
            }
        })

        function loadrekappersediaan() {
            const level_user = "{{ $level_user }}";
            // if (level_user == "direktur" || level_user == "super admin" || level_user == "gm marketing" || level_user == "gm administrasi") {
            //     $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     <div class="sk-wave-rect"></div>
            //     </div>`);
            //     $("#loadrekappersediaan").load('/dashboard/rekappersediaan');
            // }
            $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaan');

        }

        loadrekappersediaan();
    });
</script>
@endpush
