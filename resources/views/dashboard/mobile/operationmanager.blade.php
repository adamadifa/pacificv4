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
    <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-brand-shopee ti-28px"></i></span>
                    </div>
                    <h4 class="mb-0">{{ formatRupiah($penjualan->total) }}</h4>
                </div>
                <p class="mb-1">Penjualan Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success"><i class="ti ti-brand-shopee ti-28px"></i></span>
                    </div>
                    <h4 class="mb-0">{{ formatRupiah($pembayaran->total) }}</h4>
                </div>
                <p class="mb-1">Pembayaran Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12 mb-2">
        <div class="card card-border-shadow-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info"><i class="ti ti-brand-shopee ti-28px"></i></span>
                    </div>
                    <h4 class="mb-0">{{ formatRupiah($jmltransaksi) }}</h4>
                </div>
                <p class="mb-1">Jumlah Transaksi</p>
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
            const kode_cabang = formRekapkendaraan.find('#kode_cabangrekapkendaraan').val();
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
            $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaancabang');

        }
        loadrekappersediaan();
    });
</script>
@endpush
